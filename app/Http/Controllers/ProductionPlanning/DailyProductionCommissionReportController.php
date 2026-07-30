<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class DailyProductionCommissionReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('daily-production-commission-report');
        $this->initDummyData();
        View::share('activeMenu', 'daily-production-commission-report');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $employees = [
            ['id'=>'EMP-001','name'=>'Budi Santoso','machine'=>'LINE-A1'],
            ['id'=>'EMP-002','name'=>'Andi Kurniawan','machine'=>'LINE-A2'],
            ['id'=>'EMP-003','name'=>'Citra Dewi','machine'=>'LINE-B1'],
            ['id'=>'EMP-004','name'=>'Dedi Kuswanto','machine'=>'LINE-B2'],
            ['id'=>'EMP-005','name'=>'Eka Putri','machine'=>'LINE-A1'],
            ['id'=>'EMP-006','name'=>'Fajar Nugroho','machine'=>'LINE-A2'],
            ['id'=>'EMP-007','name'=>'Gilang Ramadhan','machine'=>'LINE-B1'],
            ['id'=>'EMP-008','name'=>'Hendra Wijaya','machine'=>'LINE-B2'],
        ];

        $data = [];
        for ($d = 0; $d < 5; $d++) {
            $date = date('Y-m-d', strtotime("2026-07-26 +{$d} days"));
            $shuffled = $employees;
            shuffle($shuffled);
            $active = array_slice($shuffled, 0, rand(5, 8));
            foreach ($active as $e) {
                $batch = rand(3, 8);
                $qty = $batch * rand(40, 70);
                $ratePerBatch = match($e['machine']) {
                    'LINE-A1' => 15000,
                    'LINE-A2' => 15000,
                    'LINE-B1' => 12000,
                    'LINE-B2' => 12000,
                    default => 10000,
                };
                $commission = $batch * $ratePerBatch;
                $notes = $batch >= 7 ? 'Target terlampaui' : ($batch <= 3 ? 'Kurang dari minimal' : '');
                $data[] = [
                    'date' => $date,
                    'employee_id' => $e['id'],
                    'employee_name' => $e['name'],
                    'machine_id' => $e['machine'],
                    'total_batch_handled' => $batch,
                    'total_qty_produced' => $qty,
                    'total_commission_amount' => $commission,
                    'notes' => $notes,
                ];
            }
        }
        foreach ($data as $item) { $this->store->create($item); }
    }

    public function index()
    {
        return view('production-planning.daily-production-commission-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['employee_id'] ?? '', $q) !== false ||
                stripos($i['employee_name'] ?? '', $q) !== false ||
                stripos($i['machine_id'] ?? '', $q) !== false
            );
        }

        if ($request->filled('filter_date_from')) {
            $from = $request->filter_date_from;
            $data = array_filter($data, fn($i) => ($i['date'] ?? '') >= $from);
        }
        if ($request->filled('filter_date_to')) {
            $to = $request->filter_date_to;
            $data = array_filter($data, fn($i) => ($i['date'] ?? '') <= $to);
        }

        $data = array_values($data);

        $totalCommission = array_sum(array_column($data, 'total_commission_amount'));
        $totalBatch = array_sum(array_column($data, 'total_batch_handled'));
        $totalQty = array_sum(array_column($data, 'total_qty_produced'));

        usort($data, fn($a, $b) => strcmp($a['employee_name'] ?? '', $b['employee_name'] ?? ''));

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('commission_fmt', fn($r) => 'Rp '.number_format($r['total_commission_amount'], 0, ',', '.'))
            ->rawColumns(['commission_fmt'])
            ->with(['summary' => ['total_commission' => $totalCommission, 'total_batch' => $totalBatch, 'total_qty' => $totalQty]])
            ->make(true);
    }

    public function export(Request $request)
    {
        $data = $this->store->all();
        usort($data, fn($a, $b) => strcmp($a['employee_name'] ?? '', $b['employee_name'] ?? ''));
        $filename = 'daily-production-commission-report-'.date('Y-m-d').'.csv';

        $headers = ['Content-Type: text/csv','Content-Disposition: attachment;filename="'.$filename.'"'];
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date','Employee ID','Employee Name','Machine ID','Total Batch','Total Qty (Pcs)','Total Commission','Notes']);
            foreach ($data as $row) {
                fputcsv($file, [$row['date'],$row['employee_id'],$row['employee_name'],$row['machine_id'],$row['total_batch_handled'],$row['total_qty_produced'],$row['total_commission_amount'],$row['notes']]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}