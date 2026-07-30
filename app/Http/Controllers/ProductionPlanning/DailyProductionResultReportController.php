<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class DailyProductionResultReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('daily-production-result-report');
        $this->initDummyData();
        View::share('activeMenu', 'daily-production-result-report');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $data = [
            ['date'=>'2026-07-28','production_id'=>'PRD-LST-0001','product_name'=>'Wall Paint White 20L','batch_no'=>'BN-0421','total_output_pcs'=>200,'total_output_kg'=>195,'reject_qty_kg'=>5,'yield_percent'=>97.5,'group'=>'Wall Paint','notes'=>''],
            ['date'=>'2026-07-28','production_id'=>'PRD-LST-0002','product_name'=>'Wall Paint White 20L','batch_no'=>'BN-0422','total_output_pcs'=>150,'total_output_kg'=>152,'reject_qty_kg'=>3,'yield_percent'=>98.1,'group'=>'Wall Paint','notes'=>''],
            ['date'=>'2026-07-28','production_id'=>'PRD-LST-0009','product_name'=>'Primer Grey 5L','batch_no'=>'BN-0429','total_output_pcs'=>100,'total_output_kg'=>96,'reject_qty_kg'=>1,'yield_percent'=>99.0,'group'=>'Primer','notes'=>''],
            ['date'=>'2026-07-29','production_id'=>'PRD-LST-0003','product_name'=>'Primer Grey 5L','batch_no'=>'BN-0423','total_output_pcs'=>300,'total_output_kg'=>295,'reject_qty_kg'=>8,'yield_percent'=>97.4,'group'=>'Primer','notes'=>''],
            ['date'=>'2026-07-29','production_id'=>'PRD-LST-0010','product_name'=>'Wall Paint Cream 20L','batch_no'=>'BN-0430','total_output_pcs'=>180,'total_output_kg'=>170,'reject_qty_kg'=>4,'yield_percent'=>97.7,'group'=>'Wall Paint','notes'=>''],
            ['date'=>'2026-07-29','production_id'=>'PRD-LST-0004','product_name'=>'Top Coat Clear 15L','batch_no'=>'BN-0424','total_output_pcs'=>180,'total_output_kg'=>168,'reject_qty_kg'=>6,'yield_percent'=>96.6,'group'=>'Top Coat','notes'=>''],
            ['date'=>'2026-07-30','production_id'=>'PRD-LST-0005','product_name'=>'Wall Paint Cream 20L','batch_no'=>'BN-0425','total_output_pcs'=>250,'total_output_kg'=>248,'reject_qty_kg'=>7,'yield_percent'=>97.3,'group'=>'Wall Paint','notes'=>''],
            ['date'=>'2026-07-30','production_id'=>'PRD-LST-0011','product_name'=>'Primer Putih 5L','batch_no'=>'BN-0431','total_output_pcs'=>120,'total_output_kg'=>115,'reject_qty_kg'=>2,'yield_percent'=>98.3,'group'=>'Primer','notes'=>''],
            ['date'=>'2026-07-30','production_id'=>'PRD-LST-0012','product_name'=>'Top Coat Glossy 15L','batch_no'=>'BN-0432','total_output_pcs'=>140,'total_output_kg'=>140,'reject_qty_kg'=>5,'yield_percent'=>96.6,'group'=>'Top Coat','notes'=>''],
            ['date'=>'2026-07-31','production_id'=>'PRD-LST-0006','product_name'=>'Primer Putih 5L','batch_no'=>'BN-0426','total_output_pcs'=>220,'total_output_kg'=>218,'reject_qty_kg'=>4,'yield_percent'=>98.2,'group'=>'Primer','notes'=>''],
            ['date'=>'2026-07-31','production_id'=>'PRD-LST-0013','product_name'=>'Wall Paint Blue 10L','batch_no'=>'BN-0433','total_output_pcs'=>130,'total_output_kg'=>132,'reject_qty_kg'=>3,'yield_percent'=>97.8,'group'=>'Wall Paint','notes'=>''],
            ['date'=>'2026-07-31','production_id'=>'PRD-LST-0014','product_name'=>'Cat Ekonomis 5L','batch_no'=>'BN-0434','total_output_pcs'=>200,'total_output_kg'=>188,'reject_qty_kg'=>12,'yield_percent'=>94.0,'group'=>'Ekonomis','notes'=>'Reject tinggi, cek proses'],
        ];

        foreach ($data as $item) { $this->store->create($item); }
    }

    public function index()
    {
        return view('production-planning.daily-production-result-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['production_id'] ?? '', $q) !== false ||
                stripos($i['product_name'] ?? '', $q) !== false ||
                stripos($i['batch_no'] ?? '', $q) !== false
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

        if ($request->filled('filter_group') && $request->filter_group !== 'all') {
            $g = $request->filter_group;
            $data = array_filter($data, fn($i) => ($i['group'] ?? '') === $g);
        }

        $data = array_values($data);
        $totalPcs = array_sum(array_column($data, 'total_output_pcs'));
        $totalKg = array_sum(array_column($data, 'total_output_kg'));
        $totalReject = array_sum(array_column($data, 'reject_qty_kg'));
        $totalInput = $totalKg + $totalReject;
        $avgYield = count($data) > 0 ? round(array_sum(array_column($data, 'yield_percent')) / count($data), 1) : 0;
        $rejectPct = $totalInput > 0 ? round(($totalReject / $totalInput) * 100, 1) : 0;

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('yield_badge', function ($r) {
                $y = $r['yield_percent'] ?? 0;
                if ($y >= 98) return '<span class="badge bg-success">'.$y.'%</span>';
                if ($y >= 95) return '<span class="badge bg-warning text-dark">'.$y.'%</span>';
                return '<span class="badge bg-danger">'.$y.'%</span>';
            })
            ->rawColumns(['yield_badge'])
            ->with(['summary' => ['total_pcs' => $totalPcs, 'total_kg' => $totalKg, 'total_reject' => $totalReject, 'avg_yield' => $avgYield, 'reject_pct' => $rejectPct]])
            ->make(true);
    }

    public function export(Request $request)
    {
        $data = $this->store->all();
        $filename = 'daily-production-result-report-'.date('Y-m-d').'.csv';

        $headers = ['Content-Type: text/csv','Content-Disposition: attachment;filename="'.$filename.'"'];
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date','Production ID','Product Name','Batch No','Total Output (Pcs)','Total Output (Kg)','Reject (Kg)','Yield (%)','Group','Notes']);
            foreach ($data as $row) {
                fputcsv($file, [$row['date'],$row['production_id'],$row['product_name'],$row['batch_no'],$row['total_output_pcs'],$row['total_output_kg'],$row['reject_qty_kg'],$row['yield_percent'],$row['group'],$row['notes']]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}