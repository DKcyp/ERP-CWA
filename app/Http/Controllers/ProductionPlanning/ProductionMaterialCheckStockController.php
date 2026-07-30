<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ProductionMaterialCheckStockController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('production-material-check-stock');
        $this->initDummyData();
        View::share('activeMenu', 'production-material-check-stock');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $materials = [
            ['pid'=>'MAT-001','name'=>'Titanium Dioxide','uom'=>'Kg'],
            ['pid'=>'MAT-002','name'=>'Calcium Carbonate','uom'=>'Kg'],
            ['pid'=>'MAT-003','name'=>'Acrylic Resin','uom'=>'Kg'],
            ['pid'=>'MAT-004','name'=>'Solvent Bahan','uom'=>'Liter'],
            ['pid'=>'MAT-005','name'=>'Pigment Paste','uom'=>'Kg'],
            ['pid'=>'MAT-006','name'=>'Additive Anti Foam','uom'=>'Kg'],
            ['pid'=>'MAT-007','name'=>'Water','uom'=>'Liter'],
            ['pid'=>'MAT-008','name'=>'Defoamer','uom'=>'Kg'],
            ['pid'=>'MAT-009','name'=>'Thickener','uom'=>'Kg'],
            ['pid'=>'MAT-010','name'=>'Dispex','uom'=>'Kg'],
            ['pid'=>'MAT-011','name'=>'Calcium Powder','uom'=>'Kg'],
            ['pid'=>'MAT-012','name'=>'Wax Emulsion','uom'=>'Kg'],
        ];
        $warehouses = ['Gudang Bahan Bandung','Gudang Bahan Jakarta','Gudang Bahan Surabaya'];

        $schedules = [
            ['id'=>'SCH-2026-001','date'=>'2026-07-28'],
            ['id'=>'SCH-2026-002','date'=>'2026-07-28'],
            ['id'=>'SCH-2026-003','date'=>'2026-07-29'],
            ['id'=>'SCH-2026-004','date'=>'2026-07-30'],
            ['id'=>'SCH-2026-005','date'=>'2026-07-31'],
        ];

        $data = [];
        foreach ($schedules as $s) {
            $usedMats = array_rand($materials, rand(5, 8));
            if (!is_array($usedMats)) $usedMats = [$usedMats];
            foreach ($usedMats as $mi) {
                $m = $materials[$mi];
                $totalQty = rand(100, 800);
                $currentStock = rand(50, 900);
                $shortage = max(0, $totalQty - $currentStock);
                $status = $currentStock >= $totalQty ? 'Sufficient' : 'Shortage';

                $data[] = [
                    'schedule_id' => $s['id'],
                    'schedule_date' => $s['date'],
                    'product_id' => $m['pid'],
                    'product_name' => $m['name'],
                    'total_qty' => $totalQty,
                    'current_stock' => $currentStock,
                    'uom' => $m['uom'],
                    'warehouse' => $warehouses[array_rand($warehouses)],
                    'shortage_qty' => $shortage,
                    'stock_status' => $status,
                ];
            }
        }
        foreach ($data as $item) { $this->store->create($item); }
    }

    public function index()
    {
        return view('production-planning.production-material-check-stock.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['schedule_id'] ?? '', $q) !== false ||
                stripos($i['product_id'] ?? '', $q) !== false ||
                stripos($i['product_name'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_schedule') && $request->filter_schedule !== 'all') {
            $s = $request->filter_schedule;
            $data = array_filter($data, fn($i) => ($i['schedule_id'] ?? '') === $s);
        }
        if ($request->filled('filter_warehouse') && $request->filter_warehouse !== 'all') {
            $w = $request->filter_warehouse;
            $data = array_filter($data, fn($i) => ($i['warehouse'] ?? '') === $w);
        }
        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $st = $request->filter_status;
            $data = array_filter($data, fn($i) => ($i['stock_status'] ?? '') === $st);
        }

        $data = array_values($data);

        $totalNeed = array_sum(array_column($data, 'total_qty'));
        $totalStock = array_sum(array_column($data, 'current_stock'));
        $totalShortage = array_sum(array_column($data, 'shortage_qty'));
        $sufficient = count(array_filter($data, fn($i) => $i['stock_status'] === 'Sufficient'));
        $shortageCount = count($data) - $sufficient;

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('need_fmt', fn($r) => number_format($r['total_qty'], 0, ',', '.'))
            ->addColumn('stock_fmt', fn($r) => number_format($r['current_stock'], 0, ',', '.'))
            ->addColumn('shortage_fmt', function ($r) {
                $v = $r['shortage_qty'];
                return $v > 0 ? '<span class="text-danger fw-semibold">-'.number_format($v, 0, ',', '.').'</span>' : '<span class="text-success">-</span>';
            })
            ->addColumn('status_badge', function ($r) {
                return $r['stock_status'] === 'Sufficient'
                    ? '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Sufficient</span>'
                    : '<span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i>Shortage</span>';
            })
            ->rawColumns(['need_fmt','stock_fmt','shortage_fmt','status_badge'])
            ->with(['summary' => [
                'total_need' => $totalNeed, 'total_stock' => $totalStock,
                'total_shortage' => $totalShortage, 'sufficient' => $sufficient,
                'shortage_count' => $shortageCount, 'total_records' => count($data),
            ]])
            ->make(true);
    }

    public function export(Request $request)
    {
        $data = $this->store->all();
        $filename = 'production-material-check-stock-'.date('Y-m-d').'.csv';
        $headers = ['Content-Type: text/csv','Content-Disposition: attachment;filename="'.$filename.'"'];
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Schedule ID','Schedule Date','Product ID','Product Name','Total Qty Needed','Current Stock','UOM','Warehouse','Shortage Qty','Stock Status']);
            foreach ($data as $r) {
                fputcsv($file, [$r['schedule_id'],$r['schedule_date'],$r['product_id'],$r['product_name'],$r['total_qty'],$r['current_stock'],$r['uom'],$r['warehouse'],$r['shortage_qty'],$r['stock_status']]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}