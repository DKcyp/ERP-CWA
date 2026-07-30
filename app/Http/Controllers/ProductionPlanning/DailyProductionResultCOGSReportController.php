<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class DailyProductionResultCOGSReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('daily-production-result-cogs-report');
        $this->initDummyData();
        View::share('activeMenu', 'daily-production-result-cogs-report');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $products = [
            ['name'=>'Wall Paint White 20L','group'=>'Wall Paint','mat_rate'=>1.0],
            ['name'=>'Wall Paint Cream 20L','group'=>'Wall Paint','mat_rate'=>1.05],
            ['name'=>'Wall Paint Blue 10L','group'=>'Wall Paint','mat_rate'=>0.95],
            ['name'=>'Primer Grey 5L','group'=>'Primer','mat_rate'=>0.85],
            ['name'=>'Primer Putih 5L','group'=>'Primer','mat_rate'=>0.82],
            ['name'=>'Top Coat Clear 15L','group'=>'Top Coat','mat_rate'=>1.15],
            ['name'=>'Top Coat Glossy 15L','group'=>'Top Coat','mat_rate'=>1.20],
            ['name'=>'Cat Ekonomis 5L','group'=>'Ekonomis','mat_rate'=>0.65],
        ];

        $batches = [
            ['date'=>'2026-07-28','production_id'=>'PRD-LST-0001','batch_no'=>'BN-0421','pcs'=>200,'kg'=>195],
            ['date'=>'2026-07-28','production_id'=>'PRD-LST-0002','batch_no'=>'BN-0422','pcs'=>150,'kg'=>152],
            ['date'=>'2026-07-28','production_id'=>'PRD-LST-0009','batch_no'=>'BN-0429','pcs'=>100,'kg'=>96],
            ['date'=>'2026-07-29','production_id'=>'PRD-LST-0003','batch_no'=>'BN-0423','pcs'=>300,'kg'=>295],
            ['date'=>'2026-07-29','production_id'=>'PRD-LST-0010','batch_no'=>'BN-0430','pcs'=>180,'kg'=>170],
            ['date'=>'2026-07-29','production_id'=>'PRD-LST-0004','batch_no'=>'BN-0424','pcs'=>180,'kg'=>168],
            ['date'=>'2026-07-30','production_id'=>'PRD-LST-0005','batch_no'=>'BN-0425','pcs'=>250,'kg'=>248],
            ['date'=>'2026-07-30','production_id'=>'PRD-LST-0011','batch_no'=>'BN-0431','pcs'=>120,'kg'=>115],
            ['date'=>'2026-07-30','production_id'=>'PRD-LST-0012','batch_no'=>'BN-0432','pcs'=>140,'kg'=>140],
            ['date'=>'2026-07-31','production_id'=>'PRD-LST-0006','batch_no'=>'BN-0426','pcs'=>220,'kg'=>218],
            ['date'=>'2026-07-31','production_id'=>'PRD-LST-0013','batch_no'=>'BN-0433','pcs'=>130,'kg'=>132],
            ['date'=>'2026-07-31','production_id'=>'PRD-LST-0014','batch_no'=>'BN-0434','pcs'=>200,'kg'=>188],
            ['date'=>'2026-08-01','production_id'=>'PRD-LST-0015','batch_no'=>'BN-0435','pcs'=>160,'kg'=>155],
            ['date'=>'2026-08-01','production_id'=>'PRD-LST-0016','batch_no'=>'BN-0436','pcs'=>190,'kg'=>185],
            ['date'=>'2026-08-01','production_id'=>'PRD-LST-0017','batch_no'=>'BN-0437','pcs'=>110,'kg'=>105],
        ];

        $data = [];
        foreach ($batches as $b) {
            $p = $products[array_rand($products)];
            $matCostPerKg = 18000 * $p['mat_rate'];
            $overheadPerKg = 4500;
            $laborPerKg = 3200;
            $totalMat = round($b['kg'] * $matCostPerKg);
            $totalOverhead = round($b['kg'] * $overheadPerKg);
            $totalLabor = round($b['kg'] * $laborPerKg);
            $totalCogs = $totalMat + $totalOverhead + $totalLabor;
            $cogsPerKg = round($totalCogs / $b['kg'], 0);
            $cogsPerPcs = round($totalCogs / $b['pcs'], 0);

            $data[] = [
                'date' => $b['date'],
                'production_id' => $b['production_id'],
                'product_name' => $p['name'],
                'product_group' => $p['group'],
                'batch_no' => $b['batch_no'],
                'total_output_pcs' => $b['pcs'],
                'total_output_kg' => $b['kg'],
                'total_material_cost' => $totalMat,
                'overhead_cost' => $totalOverhead,
                'labor_cost' => $totalLabor,
                'total_cogs' => $totalCogs,
                'cogs_per_kg' => $cogsPerKg,
                'cogs_per_pcs' => $cogsPerPcs,
            ];
        }
        foreach ($data as $item) { $this->store->create($item); }
    }

    public function index()
    {
        return view('production-planning.daily-production-result-cogs-report.index');
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

        $data = array_values($data);

        $totalCogs = array_sum(array_column($data, 'total_cogs'));
        $totalMat = array_sum(array_column($data, 'total_material_cost'));
        $totalOverhead = array_sum(array_column($data, 'overhead_cost'));
        $totalLabor = array_sum(array_column($data, 'labor_cost'));
        $totalKg = array_sum(array_column($data, 'total_output_kg'));
        $totalPcs = array_sum(array_column($data, 'total_output_pcs'));
        $avgCogsKg = $totalKg > 0 ? round($totalCogs / $totalKg) : 0;
        $avgCogsPcs = $totalPcs > 0 ? round($totalCogs / $totalPcs) : 0;

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('material_fmt', fn($r) => 'Rp '.number_format($r['total_material_cost'], 0, ',', '.'))
            ->addColumn('overhead_fmt', fn($r) => 'Rp '.number_format($r['overhead_cost'], 0, ',', '.'))
            ->addColumn('labor_fmt', fn($r) => 'Rp '.number_format($r['labor_cost'], 0, ',', '.'))
            ->addColumn('cogs_fmt', fn($r) => 'Rp '.number_format($r['total_cogs'], 0, ',', '.'))
            ->addColumn('cogs_kg_fmt', fn($r) => 'Rp '.number_format($r['cogs_per_kg'], 0, ',', '.'))
            ->addColumn('cogs_pcs_fmt', fn($r) => 'Rp '.number_format($r['cogs_per_pcs'], 0, ',', '.'))
            ->rawColumns(['material_fmt', 'overhead_fmt', 'labor_fmt', 'cogs_fmt', 'cogs_kg_fmt', 'cogs_pcs_fmt'])
            ->with(['summary' => [
                'total_cogs' => $totalCogs, 'total_mat' => $totalMat, 'total_overhead' => $totalOverhead,
                'total_labor' => $totalLabor, 'total_kg' => $totalKg, 'total_pcs' => $totalPcs,
                'avg_cogs_kg' => $avgCogsKg, 'avg_cogs_pcs' => $avgCogsPcs,
            ]])
            ->make(true);
    }

    public function export(Request $request)
    {
        $data = $this->store->all();
        $filename = 'daily-production-result-cogs-report-'.date('Y-m-d').'.csv';

        $headers = ['Content-Type: text/csv','Content-Disposition: attachment;filename="'.$filename.'"'];
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date','Production ID','Product Name','Batch No','Output (Pcs)','Output (Kg)','Material Cost','Overhead Cost','Labor Cost','Total COGS','COGS/Kg','COGS/Pcs']);
            foreach ($data as $row) {
                fputcsv($file, [$row['date'],$row['production_id'],$row['product_name'],$row['batch_no'],$row['total_output_pcs'],$row['total_output_kg'],$row['total_material_cost'],$row['overhead_cost'],$row['labor_cost'],$row['total_cogs'],$row['cogs_per_kg'],$row['cogs_per_pcs']]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}