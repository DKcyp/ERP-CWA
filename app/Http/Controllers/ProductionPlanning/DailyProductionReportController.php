<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class DailyProductionReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('daily-production-report');
        $this->initDummyData();
        View::share('activeMenu', 'daily-production-report');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $data = [
            ['date'=>'2026-07-28','production_id'=>'PRD-LST-0001','product_name'=>'Wall Paint White 20L','batch_no'=>'BN-0421','qty_planned_kg'=>210,'qty_actual_kg'=>195,'efficiency_percent'=>92.9,'machine_id'=>'LINE-A1','status'=>'COMPLETED','tipe'=>'Water Based','notes'=>'Produksi sesuai target'],
            ['date'=>'2026-07-28','production_id'=>'PRD-LST-0002','product_name'=>'Wall Paint White 20L','batch_no'=>'BN-0422','qty_planned_kg'=>160,'qty_actual_kg'=>152,'efficiency_percent'=>95.0,'machine_id'=>'LINE-A1','status'=>'COMPLETED','tipe'=>'Water Based','notes'=>''],
            ['date'=>'2026-07-28','production_id'=>'PRD-LST-0009','product_name'=>'Primer Grey 5L','batch_no'=>'BN-0429','qty_planned_kg'=>100,'qty_actual_kg'=>96,'efficiency_percent'=>96.0,'machine_id'=>'LINE-A2','status'=>'COMPLETED','tipe'=>'Water Based','notes'=>'Batch kecil'],
            ['date'=>'2026-07-29','production_id'=>'PRD-LST-0003','product_name'=>'Primer Grey 5L','batch_no'=>'BN-0423','qty_planned_kg'=>310,'qty_actual_kg'=>295,'efficiency_percent'=>95.2,'machine_id'=>'LINE-A2','status'=>'COMPLETED','tipe'=>'Water Based','notes'=>''],
            ['date'=>'2026-07-29','production_id'=>'PRD-LST-0010','product_name'=>'Wall Paint Cream 20L','batch_no'=>'BN-0430','qty_planned_kg'=>180,'qty_actual_kg'=>170,'efficiency_percent'=>94.4,'machine_id'=>'LINE-B1','status'=>'COMPLETED','tipe'=>'Water Based','notes'=>''],
            ['date'=>'2026-07-29','production_id'=>'PRD-LST-0004','product_name'=>'Top Coat Clear 15L','batch_no'=>'BN-0424','qty_planned_kg'=>180,'qty_actual_kg'=>168,'efficiency_percent'=>93.3,'machine_id'=>'LINE-B1','status'=>'COMPLETED','tipe'=>'Solvent Based','notes'=>''],
            ['date'=>'2026-07-30','production_id'=>'PRD-LST-0005','product_name'=>'Wall Paint Cream 20L','batch_no'=>'BN-0425','qty_planned_kg'=>260,'qty_actual_kg'=>248,'efficiency_percent'=>95.4,'machine_id'=>'LINE-A1','status'=>'COMPLETED','tipe'=>'Water Based','notes'=>''],
            ['date'=>'2026-07-30','production_id'=>'PRD-LST-0011','product_name'=>'Primer Putih 5L','batch_no'=>'BN-0431','qty_planned_kg'=>120,'qty_actual_kg'=>115,'efficiency_percent'=>95.8,'machine_id'=>'LINE-A2','status'=>'COMPLETED','tipe'=>'Water Based','notes'=>''],
            ['date'=>'2026-07-30','production_id'=>'PRD-LST-0012','product_name'=>'Top Coat Glossy 15L','batch_no'=>'BN-0432','qty_planned_kg'=>150,'qty_actual_kg'=>140,'efficiency_percent'=>93.3,'machine_id'=>'LINE-B2','status'=>'COMPLETED','tipe'=>'Solvent Based','notes'=>''],
            ['date'=>'2026-07-31','production_id'=>'PRD-LST-0006','product_name'=>'Primer Putih 5L','batch_no'=>'BN-0426','qty_planned_kg'=>230,'qty_actual_kg'=>218,'efficiency_percent'=>94.8,'machine_id'=>'LINE-A2','status'=>'COMPLETED','tipe'=>'Water Based','notes'=>''],
            ['date'=>'2026-07-31','production_id'=>'PRD-LST-0013','product_name'=>'Wall Paint Blue 10L','batch_no'=>'BN-0433','qty_planned_kg'=>140,'qty_actual_kg'=>132,'efficiency_percent'=>94.3,'machine_id'=>'LINE-B1','status'=>'COMPLETED','tipe'=>'Water Based','notes'=>''],
            ['date'=>'2026-07-31','production_id'=>'PRD-LST-0014','product_name'=>'Cat Ekonomis 5L','batch_no'=>'BN-0434','qty_planned_kg'=>200,'qty_actual_kg'=>188,'efficiency_percent'=>94.0,'machine_id'=>'LINE-B2','status'=>'COMPLETED','tipe'=>'Water Based','notes'=>''],
        ];

        foreach ($data as $item) { $this->store->create($item); }
    }

    public function index()
    {
        return view('production-planning.daily-production-report.index');
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

        if ($request->filled('filter_machine') && $request->filter_machine !== 'all') {
            $m = $request->filter_machine;
            $data = array_filter($data, fn($i) => ($i['machine_id'] ?? '') === $m);
        }

        if ($request->filled('filter_tipe') && $request->filter_tipe !== 'all') {
            $t = $request->filter_tipe;
            $data = array_filter($data, fn($i) => ($i['tipe'] ?? '') === $t);
        }

        $data = array_values($data);

        $totalBatch = count($data);
        $totalTonase = array_sum(array_column($data, 'qty_actual_kg'));
        $avgEfficiency = $totalBatch > 0 ? round(array_sum(array_column($data, 'efficiency_percent')) / $totalBatch, 1) : 0;

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('eff_badge', fn($r) => $r['efficiency_percent'] >= 95
                ? '<span class="badge bg-success">'.$r['efficiency_percent'].'%</span>'
                : ($r['efficiency_percent'] >= 90
                    ? '<span class="badge bg-warning text-dark">'.$r['efficiency_percent'].'%</span>'
                    : '<span class="badge bg-danger">'.$r['efficiency_percent'].'%</span>'))
            ->addColumn('status_badge', function ($r) {
                $map = ['COMPLETED'=>'success','IN_PROGRESS'=>'primary','PLANNED'=>'info','DRAFT'=>'secondary'];
                $c = $map[$r['status']] ?? 'secondary';
                return '<span class="badge bg-'.$c.'">'.$r['status'].'</span>';
            })
            ->rawColumns(['eff_badge', 'status_badge'])
            ->with(['summary' => ['total_batch' => $totalBatch, 'total_tonase' => $totalTonase, 'avg_efficiency' => $avgEfficiency]])
            ->make(true);
    }

    public function export(Request $request)
    {
        $data = $this->store->all();
        $filename = 'daily-production-report-'.date('Y-m-d').'.csv';

        $headers = ['Content-Type: text/csv','Content-Disposition: attachment;filename="'.$filename.'"'];
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date','Production ID','Product Name','Batch No','Qty Planned (Kg)','Qty Actual (Kg)','Efficiency (%)','Machine ID','Status','Tipe','Notes']);
            foreach ($data as $row) {
                fputcsv($file, [$row['date'],$row['production_id'],$row['product_name'],$row['batch_no'],$row['qty_planned_kg'],$row['qty_actual_kg'],$row['efficiency_percent'],$row['machine_id'],$row['status'],$row['tipe'],$row['notes']]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}