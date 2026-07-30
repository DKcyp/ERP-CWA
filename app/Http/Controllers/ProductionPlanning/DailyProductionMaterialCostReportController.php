<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class DailyProductionMaterialCostReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('daily-production-material-cost-report');
        $this->initDummyData();
        View::share('activeMenu', 'daily-production-material-cost-report');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $materials = [
            ['id'=>'MAT-001','name'=>'Titanium Dioxide','uom'=>'Kg','unit_cost'=>45000],
            ['id'=>'MAT-002','name'=>'Calcium Carbonate','uom'=>'Kg','unit_cost'=>3200],
            ['id'=>'MAT-003','name'=>'Acrylic Resin','uom'=>'Kg','unit_cost'=>38000],
            ['id'=>'MAT-004','name'=>'Solvent Bahan','uom'=>'Liter','unit_cost'=>18500],
            ['id'=>'MAT-005','name'=>'Pigment Paste','uom'=>'Kg','unit_cost'=>62000],
            ['id'=>'MAT-006','name'=>'Additive Anti Foam','uom'=>'Kg','unit_cost'=>85000],
            ['id'=>'MAT-007','name'=>'Water','uom'=>'Liter','unit_cost'=>50],
            ['id'=>'MAT-008','name'=>'Defoamer','uom'=>'Kg','unit_cost'=>52000],
            ['id'=>'MAT-009','name'=>'Thickener','uom'=>'Kg','unit_cost'=>78000],
            ['id'=>'MAT-010','name'=>'Dispex','uom'=>'Kg','unit_cost'=>45000],
        ];

        $batches = [
            ['production_id'=>'PRD-LST-0001','batch_no'=>'BN-0421','date'=>'2026-07-28'],
            ['production_id'=>'PRD-LST-0002','batch_no'=>'BN-0422','date'=>'2026-07-28'],
            ['production_id'=>'PRD-LST-0009','batch_no'=>'BN-0429','date'=>'2026-07-28'],
            ['production_id'=>'PRD-LST-0003','batch_no'=>'BN-0423','date'=>'2026-07-29'],
            ['production_id'=>'PRD-LST-0010','batch_no'=>'BN-0430','date'=>'2026-07-29'],
            ['production_id'=>'PRD-LST-0004','batch_no'=>'BN-0424','date'=>'2026-07-29'],
            ['production_id'=>'PRD-LST-0005','batch_no'=>'BN-0425','date'=>'2026-07-30'],
            ['production_id'=>'PRD-LST-0011','batch_no'=>'BN-0431','date'=>'2026-07-30'],
            ['production_id'=>'PRD-LST-0012','batch_no'=>'BN-0432','date'=>'2026-07-30'],
            ['production_id'=>'PRD-LST-0006','batch_no'=>'BN-0426','date'=>'2026-07-31'],
            ['production_id'=>'PRD-LST-0013','batch_no'=>'BN-0433','date'=>'2026-07-31'],
            ['production_id'=>'PRD-LST-0014','batch_no'=>'BN-0434','date'=>'2026-07-31'],
        ];

        $data = [];
        foreach ($batches as $b) {
            $usedMats = array_rand($materials, rand(5, 8));
            if (!is_array($usedMats)) $usedMats = [$usedMats];
            foreach ($usedMats as $mi) {
                $m = $materials[$mi];
                $qty = round(rand(50, 500) / 10, 1);
                $data[] = [
                    'date' => $b['date'],
                    'production_id' => $b['production_id'],
                    'batch_no' => $b['batch_no'],
                    'material_id' => $m['id'],
                    'material_name' => $m['name'],
                    'qty_used' => $qty,
                    'uom' => $m['uom'],
                    'unit_cost' => $m['unit_cost'],
                    'total_material_cost' => round($qty * $m['unit_cost']),
                ];
            }
        }
        foreach ($data as $item) { $this->store->create($item); }
    }

    public function index()
    {
        return view('production-planning.daily-production-material-cost-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['production_id'] ?? '', $q) !== false ||
                stripos($i['batch_no'] ?? '', $q) !== false ||
                stripos($i['material_id'] ?? '', $q) !== false ||
                stripos($i['material_name'] ?? '', $q) !== false
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

        $totalCost = array_sum(array_column($data, 'total_material_cost'));

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('unit_cost_fmt', fn($r) => 'Rp '.number_format($r['unit_cost'], 0, ',', '.'))
            ->addColumn('total_cost_fmt', fn($r) => 'Rp '.number_format($r['total_material_cost'], 0, ',', '.'))
            ->rawColumns(['unit_cost_fmt', 'total_cost_fmt'])
            ->with(['summary' => ['total_cost' => $totalCost]])
            ->make(true);
    }

    public function export(Request $request)
    {
        $data = $this->store->all();
        $filename = 'daily-production-material-cost-report-'.date('Y-m-d').'.csv';

        $headers = ['Content-Type: text/csv','Content-Disposition: attachment;filename="'.$filename.'"'];
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date','Production ID','Batch No','Material ID','Material Name','Qty Used','UOM','Unit Cost','Total Material Cost']);
            foreach ($data as $row) {
                fputcsv($file, [$row['date'],$row['production_id'],$row['batch_no'],$row['material_id'],$row['material_name'],$row['qty_used'],$row['uom'],$row['unit_cost'],$row['total_material_cost']]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}