<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ProductStockMinusReportController extends Controller
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

        $createdIds = [];
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
                $item = $this->store->create([
                    'product_id' => $p['product_id'],
                    'name' => $p['name'],
                    'category' => $p['category'],
                    'warehouse' => $wh,
                    'current_stock' => $cur,
                    'reserved_stock' => $res,
                    'uom' => $p['uom'],
                ]);
                $createdIds[] = $item['id'];
            }
        }

        $patched = 0;
        foreach ($createdIds as $id) {
            if ($patched >= 6) break;
            $item = $this->store->find($id);
            if (!$item) continue;
            $this->store->update($id, ['reserved_stock' => $item['current_stock'] + rand(20, 150)]);
            $patched++;
        }
    }

    public function index()
    {
        $data = $this->store->all();
        $minusCount = 0;
        foreach ($data as $item) {
            $avail = ($item['current_stock'] ?? 0) - ($item['reserved_stock'] ?? 0);
            if ($avail < 0) $minusCount++;
        }
        return view('material-management.product-stock-minus-report', compact('minusCount'));
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        $minus = array_values(array_filter($data, function ($i) {
            return (($i['current_stock'] ?? 0) - ($i['reserved_stock'] ?? 0)) < 0;
        }));

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $minus = array_values(array_filter($minus, fn($i) =>
                stripos($i['product_id'] ?? '', $q) !== false ||
                stripos($i['name'] ?? '', $q) !== false
            ));
        }
        if ($request->filled('filter_warehouse') && $request->filter_warehouse !== 'all') {
            $minus = array_values(array_filter($minus, fn($i) => ($i['warehouse'] ?? '') === $request->filter_warehouse));
        }

        return DataTables::of($minus)
            ->addIndexColumn()
            ->addColumn('available_stock', function ($r) {
                return ($r['current_stock'] ?? 0) - ($r['reserved_stock'] ?? 0);
            })
            ->addColumn('current_fmt', fn($r) => number_format($r['current_stock'] ?? 0, 0, ',', '.'))
            ->addColumn('reserved_fmt', fn($r) => number_format($r['reserved_stock'] ?? 0, 0, ',', '.'))
            ->addColumn('available_fmt', function ($r) {
                $avail = ($r['current_stock'] ?? 0) - ($r['reserved_stock'] ?? 0);
                return '<span class="text-danger fw-bold fs-6">'.number_format($avail, 0, ',', '.').'</span>';
            })
            ->addColumn('status_badge', function ($r) {
                $avail = ($r['current_stock'] ?? 0) - ($r['reserved_stock'] ?? 0);
                if ($avail < -100) return '<span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i>Critical</span>';
                if ($avail < -50) return '<span class="badge bg-danger"><i class="bi bi-exclamation-circle me-1"></i>Severe</span>';
                return '<span class="badge bg-warning text-dark"><i class="bi bi-dash-circle me-1"></i>Minor</span>';
            })
            ->rawColumns(['current_fmt','reserved_fmt','available_fmt','status_badge'])
            ->make(true);
    }
}
