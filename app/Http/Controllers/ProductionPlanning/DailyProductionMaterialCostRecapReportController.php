<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class DailyProductionMaterialCostRecapReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('daily-production-material-cost-recap-report');
        $this->initDummyData();
        View::share('activeMenu', 'daily-production-material-cost-recap-report');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $groups = [
            ['name'=>'Wall Paint','std_cost_per_kg'=>18000,'prod_range'=>[8,15]],
            ['name'=>'Primer','std_cost_per_kg'=>15500,'prod_range'=>[5,10]],
            ['name'=>'Top Coat','std_cost_per_kg'=>21000,'prod_range'=>[4,8]],
            ['name'=>'Ekonomis','std_cost_per_kg'=>12000,'prod_range'=>[3,6]],
        ];

        $periods = [];
        for ($d = 0; $d < 15; $d++) {
            $periods[] = date('Y-m-d', strtotime("2026-07-17 +{$d} days"));
        }

        $data = [];
        foreach ($periods as $date) {
            foreach ($groups as $g) {
                $prodCount = rand($g['prod_range'][0], $g['prod_range'][1]);
                $totalKg = $prodCount * rand(150, 250);
                $actualCostPerKg = $g['std_cost_per_kg'] * (0.92 + (rand(0, 16) / 100));
                $totalMatCost = round($totalKg * $actualCostPerKg);
                $avgCost = round($totalMatCost / $totalKg);
                $variance = round((($avgCost - $g['std_cost_per_kg']) / $g['std_cost_per_kg']) * 100, 1);

                $data[] = [
                    'period' => $date,
                    'product_group' => $g['name'],
                    'standard_cost_per_kg' => $g['std_cost_per_kg'],
                    'total_production_count' => $prodCount,
                    'total_kg' => $totalKg,
                    'total_material_cost_accumulated' => $totalMatCost,
                    'average_cost_per_kg' => $avgCost,
                    'variance_to_standard' => $variance,
                ];
            }
        }
        foreach ($data as $item) { $this->store->create($item); }
    }

    public function index()
    {
        return view('production-planning.daily-production-material-cost-recap-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) => stripos($i['product_group'] ?? '', $q) !== false);
        }

        if ($request->filled('filter_date_from')) {
            $from = $request->filter_date_from;
            $data = array_filter($data, fn($i) => ($i['period'] ?? '') >= $from);
        }
        if ($request->filled('filter_date_to')) {
            $to = $request->filter_date_to;
            $data = array_filter($data, fn($i) => ($i['period'] ?? '') <= $to);
        }

        if ($request->filled('filter_group') && $request->filter_group !== 'all') {
            $g = $request->filter_group;
            $data = array_filter($data, fn($i) => ($i['product_group'] ?? '') === $g);
        }

        $data = array_values($data);

        $totalCost = array_sum(array_column($data, 'total_material_cost_accumulated'));
        $totalProd = array_sum(array_column($data, 'total_production_count'));
        $totalKg = array_sum(array_column($data, 'total_kg'));
        $avgCost = $totalKg > 0 ? round($totalCost / $totalKg) : 0;
        $avgVariance = count($data) > 0 ? round(array_sum(array_column($data, 'variance_to_standard')) / count($data), 1) : 0;

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('period_fmt', fn($r) => \Carbon\Carbon::parse($r['period'])->format('d/m/Y'))
            ->addColumn('total_cost_fmt', fn($r) => 'Rp '.number_format($r['total_material_cost_accumulated'], 0, ',', '.'))
            ->addColumn('avg_cost_fmt', fn($r) => 'Rp '.number_format($r['average_cost_per_kg'], 0, ',', '.'))
            ->addColumn('std_cost_fmt', fn($r) => 'Rp '.number_format($r['standard_cost_per_kg'], 0, ',', '.'))
            ->addColumn('variance_badge', function ($r) {
                $v = $r['variance_to_standard'];
                $abs = abs($v);
                if ($v > 0) {
                    if ($abs > 5) return '<span class="badge bg-danger"><i class="bi bi-arrow-up-short me-1"></i>+'.number_format($v,1).'%</span>';
                    return '<span class="badge bg-warning text-dark"><i class="bi bi-arrow-up-short me-1"></i>+'.number_format($v,1).'%</span>';
                }
                if ($v < 0) {
                    if ($abs > 5) return '<span class="badge bg-success"><i class="bi bi-arrow-down-short me-1"></i>'.number_format($v,1).'%</span>';
                    return '<span class="badge bg-info"><i class="bi bi-arrow-down-short me-1"></i>'.number_format($v,1).'%</span>';
                }
                return '<span class="badge bg-secondary">0.0%</span>';
            })
            ->rawColumns(['total_cost_fmt', 'avg_cost_fmt', 'std_cost_fmt', 'variance_badge'])
            ->with(['summary' => [
                'total_cost' => $totalCost, 'total_prod' => $totalProd, 'total_kg' => $totalKg,
                'avg_cost' => $avgCost, 'avg_variance' => $avgVariance,
            ]])
            ->make(true);
    }

    public function chart(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_date_from')) {
            $from = $request->filter_date_from;
            $data = array_filter($data, fn($i) => ($i['period'] ?? '') >= $from);
        }
        if ($request->filled('filter_date_to')) {
            $to = $request->filter_date_to;
            $data = array_filter($data, fn($i) => ($i['period'] ?? '') <= $to);
        }
        if ($request->filled('filter_group') && $request->filter_group !== 'all') {
            $g = $request->filter_group;
            $data = array_filter($data, fn($i) => ($i['product_group'] ?? '') === $g);
        }

        $grouped = [];
        foreach ($data as $row) {
            $grouped[$row['product_group']][] = $row;
        }

        $dates = array_unique(array_column($data, 'period'));
        sort($dates);
        $labels = array_map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'), $dates);

        $colors = ['Wall Paint'=>'#4e73df','Primer'=>'#1cc88a','Top Coat'=>'#36b9cc','Ekonomis'=>'#f6c23e'];
        $datasets = [];
        foreach ($grouped as $group => $rows) {
            $byDate = [];
            foreach ($rows as $r) { $byDate[$r['period']] = $r['average_cost_per_kg']; }
            $values = [];
            foreach ($dates as $d) { $values[] = $byDate[$d] ?? null; }
            $datasets[] = [
                'label' => $group,
                'data' => $values,
                'borderColor' => $colors[$group] ?? '#888',
                'backgroundColor' => ($colors[$group] ?? '#888').'33',
                'tension' => 0.3, 'fill' => false, 'pointRadius' => 2,
            ];
        }

        return response()->json(['labels' => $labels, 'datasets' => $datasets]);
    }

    public function export(Request $request)
    {
        $data = $this->store->all();
        $filename = 'daily-production-material-cost-recap-report-'.date('Y-m-d').'.csv';

        $headers = ['Content-Type: text/csv','Content-Disposition: attachment;filename="'.$filename.'"'];
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Period','Product Group','Total Production Count','Total Kg','Total Material Cost','Average Cost/Kg','Standard Cost/Kg','Variance (%)']);
            foreach ($data as $row) {
                fputcsv($file, [$row['period'],$row['product_group'],$row['total_production_count'],$row['total_kg'],$row['total_material_cost_accumulated'],$row['average_cost_per_kg'],$row['standard_cost_per_kg'],$row['variance_to_standard']]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}