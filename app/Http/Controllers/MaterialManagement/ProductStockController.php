<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ProductStockController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('material-stock');
        $this->initDummyData();
        View::share('activeMenu', 'material-management');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $products = [
            ['product_id' => 'PRD-BB-0001', 'name' => 'Resin Polyester White', 'category' => 'Bahan Baku', 'uom' => 'Kg'],
            ['product_id' => 'PRD-BB-0002', 'name' => 'Resin Epoxy Clear', 'category' => 'Bahan Baku', 'uom' => 'Kg'],
            ['product_id' => 'PRD-BB-0003', 'name' => 'Talc Powder 400 Mesh', 'category' => 'Bahan Baku', 'uom' => 'Kg'],
            ['product_id' => 'PRD-BB-0004', 'name' => 'Titanium Dioxide R-706', 'category' => 'Bahan Baku', 'uom' => 'Kg'],
            ['product_id' => 'PRD-BB-0005', 'name' => 'Calcium Carbonate', 'category' => 'Bahan Baku', 'uom' => 'Kg'],
            ['product_id' => 'PRD-BB-0006', 'name' => 'Silica Sand', 'category' => 'Bahan Baku', 'uom' => 'Kg'],
            ['product_id' => 'PRD-BB-0007', 'name' => 'Pigment Oxide Red', 'category' => 'Bahan Baku', 'uom' => 'Kg'],
            ['product_id' => 'PRD-BB-0008', 'name' => 'Pigment Carbon Black', 'category' => 'Bahan Baku', 'uom' => 'Kg'],
            ['product_id' => 'PRD-BB-0009', 'name' => 'Pigment Yellow Oxide', 'category' => 'Bahan Baku', 'uom' => 'Kg'],
            ['product_id' => 'PRD-BB-0010', 'name' => 'Alkyd Resin Medium', 'category' => 'Bahan Baku', 'uom' => 'Kg'],
            ['product_id' => 'PRD-BB-0011', 'name' => 'Acrylic Emulsion', 'category' => 'Bahan Baku', 'uom' => 'Kg'],
            ['product_id' => 'PRD-BB-0012', 'name' => 'Mineral Spirit', 'category' => 'Bahan Baku', 'uom' => 'L'],
            ['product_id' => 'PRD-BN-0001', 'name' => 'Thinner A Special', 'category' => 'Penolong', 'uom' => 'L'],
            ['product_id' => 'PRD-BN-0002', 'name' => 'Defoamer AF-200', 'category' => 'Penolong', 'uom' => 'Kg'],
            ['product_id' => 'PRD-BN-0003', 'name' => 'Dispersing Agent', 'category' => 'Penolong', 'uom' => 'Kg'],
            ['product_id' => 'PRD-BN-0004', 'name' => 'Anti-Rust Agent', 'category' => 'Penolong', 'uom' => 'Kg'],
            ['product_id' => 'PRD-BN-0005', 'name' => 'Wax Emulsion', 'category' => 'Penolong', 'uom' => 'Kg'],
            ['product_id' => 'PRD-WP-0001', 'name' => 'Wall Paint White Base', 'category' => 'WIP', 'uom' => 'Kg'],
            ['product_id' => 'PRD-WP-0002', 'name' => 'Wall Paint Cream Base', 'category' => 'WIP', 'uom' => 'Kg'],
            ['product_id' => 'PRD-WP-0003', 'name' => 'Primer Grey Base', 'category' => 'WIP', 'uom' => 'Kg'],
            ['product_id' => 'PRD-FG-0001', 'name' => 'Wall Paint White 20L', 'category' => 'Finished Goods', 'uom' => 'Pcs'],
            ['product_id' => 'PRD-FG-0002', 'name' => 'Wall Paint Cream 10L', 'category' => 'Finished Goods', 'uom' => 'Pcs'],
            ['product_id' => 'PRD-FG-0003', 'name' => 'Primer Grey 5L', 'category' => 'Finished Goods', 'uom' => 'Pcs'],
            ['product_id' => 'PRD-FG-0004', 'name' => 'Top Coat Clear 15L', 'category' => 'Finished Goods', 'uom' => 'Pcs'],
            ['product_id' => 'PRD-FG-0005', 'name' => 'Cat Ekonomis 5L', 'category' => 'Finished Goods', 'uom' => 'Pcs'],
        ];

        $warehouses = ['Gudang Bahan Bandung','Gudang Bahan Jakarta','Gudang WIP Bandung','Gudang Jadi Bandung','Gudang Jadi Jakarta'];

        foreach ($products as $p) {
            $whCount = $p['category'] === 'WIP' ? 1 : ($p['category'] === 'Finished Goods' ? 2 : 3);
            $whSlice = array_slice($warehouses, 0, $whCount);
            foreach ($whSlice as $wh) {
                $cur = match($p['category']) {
                    'Bahan Baku' => rand(200, 5000),
                    'Penolong' => rand(100, 1500),
                    'WIP' => rand(50, 800),
                    'Finished Goods' => rand(20, 500),
                    default => rand(100, 1000),
                };
                $res = rand(0, intdiv($cur, 4));
                $this->store->create([
                    'product_id' => $p['product_id'],
                    'name' => $p['name'],
                    'category' => $p['category'],
                    'warehouse' => $wh,
                    'current_stock' => $cur,
                    'reserved_stock' => $res,
                    'uom' => $p['uom'],
                ]);
            }
        }
    }

    public function index()
    {
        $data = $this->store->all();
        $totalCurrent = array_sum(array_column($data, 'current_stock'));
        $totalReserved = array_sum(array_column($data, 'reserved_stock'));
        $warehouses = array_unique(array_column($data, 'warehouse'));
        $categories = array_unique(array_column($data, 'category'));

        return view('material-management.product-stock', compact('data', 'totalCurrent', 'totalReserved', 'warehouses', 'categories'));
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
            $data = array_filter($data, fn($i) => ($i['warehouse'] ?? '') === $request->filter_warehouse);
        }
        if ($request->filled('filter_category') && $request->filter_category !== 'all') {
            $data = array_filter($data, fn($i) => ($i['category'] ?? '') === $request->filter_category);
        }

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('available_stock', fn($r) => ($r['current_stock'] ?? 0) - ($r['reserved_stock'] ?? 0))
            ->addColumn('available_stock_fmt', function ($r) {
                $available = ($r['current_stock'] ?? 0) - ($r['reserved_stock'] ?? 0);
                $cls = $available <= 0 ? 'text-danger fw-bold' : ($available < 100 ? 'text-warning fw-bold' : 'text-success');
                return '<span class="'.$cls.'">'.number_format($available, 0, ',', '.').'</span>';
            })
            ->addColumn('current_stock_fmt', fn($r) => number_format($r['current_stock'] ?? 0, 0, ',', '.'))
            ->addColumn('reserved_stock_fmt', fn($r) => number_format($r['reserved_stock'] ?? 0, 0, ',', '.'))
            ->addColumn('category_badge', function ($r) {
                return match($r['category'] ?? '') {
                    'Bahan Baku' => '<span class="badge bg-primary"><i class="bi bi-box me-1"></i>Bahan Baku</span>',
                    'Penolong' => '<span class="badge bg-info"><i class="bi bi-box-seam me-1"></i>Penolong</span>',
                    'WIP' => '<span class="badge bg-warning text-dark"><i class="bi bi-gear me-1"></i>WIP</span>',
                    'Finished Goods' => '<span class="badge bg-success"><i class="bi bi-box-check me-1"></i>Finished Goods</span>',
                    default => '<span class="badge bg-secondary">'.$r['category'].'</span>',
                };
            })
            ->rawColumns(['available_stock_fmt','current_stock_fmt','reserved_stock_fmt','category_badge'])
            ->make(true);
    }
}
