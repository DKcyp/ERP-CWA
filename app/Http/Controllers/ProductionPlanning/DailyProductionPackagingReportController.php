<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class DailyProductionPackagingReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('daily-production-packaging-report');
        $this->initDummyData();
        View::share('activeMenu', 'daily-production-packaging-report');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $packaging = [
            ['type'=>'Kaleng','unit_cost'=>2800],
            ['type'=>'Galon','unit_cost'=>3500],
            ['type'=>'Pail','unit_cost'=>5200],
            ['type'=>'Box Kardus','unit_cost'=>1800],
            ['type'=>'Shrink Wrap','unit_cost'=>450],
        ];

        $data = [];
        for ($d = 0; $d < 5; $d++) {
            $date = date('Y-m-d', strtotime("2026-07-26 +{$d} days"));
            $used = array_rand($packaging, rand(3, 5));
            if (!is_array($used)) $used = [$used];
            foreach ($used as $pi) {
                $p = $packaging[$pi];
                $qtyUsed = rand(100, 800);
                $damageRate = match($p['type']) {
                    'Kaleng' => rand(1, 4) / 100,
                    'Galon' => rand(2, 5) / 100,
                    'Pail' => rand(1, 3) / 100,
                    'Box Kardus' => rand(3, 8) / 100,
                    'Shrink Wrap' => rand(5, 12) / 100,
                    default => 0.03,
                };
                $damaged = (int) ceil($qtyUsed * $damageRate);
                $data[] = [
                    'date' => $date,
                    'production_id' => 'PRD-LST-'.str_pad(rand(1, 17), 4, '0', STR_PAD_LEFT),
                    'package_type' => $p['type'],
                    'qty_used_pcs' => $qtyUsed,
                    'qty_damaged_pcs' => $damaged,
                    'unit_packaging_cost' => $p['unit_cost'],
                    'total_packaging_cost' => $qtyUsed * $p['unit_cost'],
                    'notes' => $damaged > $qtyUsed * 0.06 ? 'Damage rate tinggi, cek supplier' : '',
                ];
            }
        }
        foreach ($data as $item) { $this->store->create($item); }
    }

    public function index()
    {
        return view('production-planning.daily-production-packaging-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['production_id'] ?? '', $q) !== false ||
                stripos($i['package_type'] ?? '', $q) !== false
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

        if ($request->filled('filter_type') && $request->filter_type !== 'all') {
            $t = $request->filter_type;
            $data = array_filter($data, fn($i) => ($i['package_type'] ?? '') === $t);
        }

        $data = array_values($data);

        $totalCost = array_sum(array_column($data, 'total_packaging_cost'));
        $totalUsed = array_sum(array_column($data, 'qty_used_pcs'));
        $totalDamaged = array_sum(array_column($data, 'qty_damaged_pcs'));
        $avgDamageRate = $totalUsed > 0 ? round(($totalDamaged / $totalUsed) * 100, 1) : 0;

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('unit_cost_fmt', fn($r) => 'Rp '.number_format($r['unit_packaging_cost'], 0, ',', '.'))
            ->addColumn('total_cost_fmt', fn($r) => 'Rp '.number_format($r['total_packaging_cost'], 0, ',', '.'))
            ->addColumn('damage_rate', function ($r) {
                $rate = $r['qty_used_pcs'] > 0 ? round(($r['qty_damaged_pcs'] / $r['qty_used_pcs']) * 100, 1) : 0;
                if ($rate > 6) return '<span class="badge bg-danger">'.$rate.'%</span>';
                if ($rate > 3) return '<span class="badge bg-warning text-dark">'.$rate.'%</span>';
                return '<span class="badge bg-success">'.$rate.'%</span>';
            })
            ->rawColumns(['unit_cost_fmt', 'total_cost_fmt', 'damage_rate'])
            ->with(['summary' => [
                'total_cost' => $totalCost, 'total_used' => $totalUsed,
                'total_damaged' => $totalDamaged, 'avg_damage_rate' => $avgDamageRate,
            ]])
            ->make(true);
    }

    public function export(Request $request)
    {
        $data = $this->store->all();
        $filename = 'daily-production-packaging-report-'.date('Y-m-d').'.csv';

        $headers = ['Content-Type: text/csv','Content-Disposition: attachment;filename="'.$filename.'"'];
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date','Production ID','Package Type','Qty Used (Pcs)','Qty Damaged (Pcs)','Damage Rate (%)','Unit Cost','Total Cost','Notes']);
            foreach ($data as $row) {
                $rate = $row['qty_used_pcs'] > 0 ? round(($row['qty_damaged_pcs'] / $row['qty_used_pcs']) * 100, 1) : 0;
                fputcsv($file, [$row['date'],$row['production_id'],$row['package_type'],$row['qty_used_pcs'],$row['qty_damaged_pcs'],$rate,$row['unit_packaging_cost'],$row['total_packaging_cost'],$row['notes']]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}