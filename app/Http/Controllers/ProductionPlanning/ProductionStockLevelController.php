<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ProductionStockLevelController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('production-stock-level');
        $this->initDummyData();
        View::share('activeMenu', 'production-stock-level');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $items = [
            ['pid'=>'MAT-001','name'=>'Titanium Dioxide','uom'=>'Kg','cat'=>'Bahan Baku'],
            ['pid'=>'MAT-002','name'=>'Calcium Carbonate','uom'=>'Kg','cat'=>'Bahan Baku'],
            ['pid'=>'MAT-003','name'=>'Acrylic Resin','uom'=>'Kg','cat'=>'Bahan Baku'],
            ['pid'=>'MAT-004','name'=>'Solvent Bahan','uom'=>'Liter','cat'=>'Bahan Baku'],
            ['pid'=>'MAT-005','name'=>'Pigment Paste','uom'=>'Kg','cat'=>'Bahan Baku'],
            ['pid'=>'MAT-006','name'=>'Additive Anti Foam','uom'=>'Kg','cat'=>'Bahan Penolong'],
            ['pid'=>'MAT-007','name'=>'Water','uom'=>'Liter','cat'=>'Bahan Penolong'],
            ['pid'=>'MAT-008','name'=>'Defoamer','uom'=>'Kg','cat'=>'Bahan Penolong'],
            ['pid'=>'MAT-009','name'=>'Thickener','uom'=>'Kg','cat'=>'Bahan Penolong'],
            ['pid'=>'MAT-010','name'=>'Dispex','uom'=>'Kg','cat'=>'Bahan Penolong'],
            ['pid'=>'WIP-001','name'=>'Wall Paint White (WIP)','uom'=>'Kg','cat'=>'WIP'],
            ['pid'=>'WIP-002','name'=>'Primer Grey (WIP)','uom'=>'Kg','cat'=>'WIP'],
            ['pid'=>'WIP-003','name'=>'Top Coat Clear (WIP)','uom'=>'Kg','cat'=>'WIP'],
            ['pid'=>'FG-001','name'=>'Wall Paint White 20L (Jadi)','uom'=>'Pcs','cat'=>'Finished Goods'],
            ['pid'=>'FG-002','name'=>'Primer Grey 5L (Jadi)','uom'=>'Pcs','cat'=>'Finished Goods'],
        ];
        $warehouses = ['Gudang Bahan Bandung','Gudang Bahan Jakarta','Gudang Bahan Surabaya','Gudang WIP Bandung','Gudang Jadi Bandung'];

        $data = [];
        foreach ($items as $item) {
            $whCount = $item['cat'] === 'WIP' ? 1 : ($item['cat'] === 'Finished Goods' ? 1 : rand(1, 3));
            $whs = $item['cat'] === 'WIP' ? ['Gudang WIP Bandung'] : ($item['cat'] === 'Finished Goods' ? ['Gudang Jadi Bandung'] : array_slice($warehouses, 0, $whCount));
            foreach ($whs as $wh) {
                $current = rand(50, 2000);
                $reserved = rand(0, min($current, 500));
                $available = $current - $reserved;
                $data[] = [
                    'product_id' => $item['pid'],
                    'name' => $item['name'],
                    'category' => $item['cat'],
                    'warehouse' => $wh,
                    'current_stock' => $current,
                    'reserved_stock' => $reserved,
                    'available_stock' => $available,
                    'uom' => $item['uom'],
                ];
            }
        }
        foreach ($data as $item) { $this->store->create($item); }
    }

    public function index()
    {
        return view('production-planning.production-stock-level.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['product_id'] ?? '', $q) !== false ||
                stripos($i['name'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_warehouse') && $request->filter_warehouse !== 'all') {
            $w = $request->filter_warehouse;
            $data = array_filter($data, fn($i) => ($i['warehouse'] ?? '') === $w);
        }
        if ($request->filled('filter_category') && $request->filter_category !== 'all') {
            $c = $request->filter_category;
            $data = array_filter($data, fn($i) => ($i['category'] ?? '') === $c);
        }

        $data = array_values($data);

        $totalCurrent = array_sum(array_column($data, 'current_stock'));
        $totalReserved = array_sum(array_column($data, 'reserved_stock'));
        $totalAvailable = array_sum(array_column($data, 'available_stock'));

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('current_fmt', fn($r) => number_format($r['current_stock'], 0, ',', '.'))
            ->addColumn('reserved_fmt', fn($r) => number_format($r['reserved_stock'], 0, ',', '.'))
            ->addColumn('available_fmt', function ($r) {
                $v = $r['available_stock'];
                $cls = $v <= 0 ? 'text-danger fw-bold' : ($v < 100 ? 'text-warning fw-semibold' : 'text-success');
                return '<span class="'.$cls.'">'.number_format($v, 0, ',', '.').'</span>';
            })
            ->addColumn('cat_badge', function ($r) {
                $c = $r['category'] ?? '';
                return match($c) {
                    'Bahan Baku' => '<span class="badge bg-primary">Bahan Baku</span>',
                    'Bahan Penolong' => '<span class="badge bg-info">Penolong</span>',
                    'WIP' => '<span class="badge bg-warning text-dark">WIP</span>',
                    'Finished Goods' => '<span class="badge bg-success">Finished</span>',
                    default => '<span class="badge bg-secondary">'.$c.'</span>',
                };
            })
            ->rawColumns(['current_fmt','reserved_fmt','available_fmt','cat_badge'])
            ->with(['summary' => [
                'total_current' => $totalCurrent, 'total_reserved' => $totalReserved,
                'total_available' => $totalAvailable, 'total_records' => count($data),
            ]])
            ->make(true);
    }

    public function refresh(Request $request)
    {
        $this->store->overwriteAll([]);
        $this->initDummyData();
        return response()->json(['success' => true, 'message' => 'Data stok berhasil disegarkan.', 'timestamp' => now()->format('d/m/Y H:i:s')]);
    }
}