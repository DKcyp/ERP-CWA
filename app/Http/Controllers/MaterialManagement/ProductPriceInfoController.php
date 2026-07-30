<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ProductPriceInfoController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('product-price-info');
        $this->initDummyData();
        View::share('activeMenu', 'material-management');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $products = [
            ['product_id' => 'PRD-BB-0001', 'name' => 'Resin Polyester White', 'category' => 'Bahan Baku', 'base_cost' => 8500, 'currency' => 'IDR'],
            ['product_id' => 'PRD-BB-0002', 'name' => 'Resin Epoxy Clear', 'category' => 'Bahan Baku', 'base_cost' => 12000, 'currency' => 'IDR'],
            ['product_id' => 'PRD-BB-0003', 'name' => 'Talc Powder 400 Mesh', 'category' => 'Bahan Baku', 'base_cost' => 3200, 'currency' => 'IDR'],
            ['product_id' => 'PRD-BB-0004', 'name' => 'Titanium Dioxide R-706', 'category' => 'Bahan Baku', 'base_cost' => 17500, 'currency' => 'IDR'],
            ['product_id' => 'PRD-BB-0005', 'name' => 'Calcium Carbonate', 'category' => 'Bahan Baku', 'base_cost' => 2800, 'currency' => 'IDR'],
            ['product_id' => 'PRD-BB-0006', 'name' => 'Silica Sand', 'category' => 'Bahan Baku', 'base_cost' => 1500, 'currency' => 'IDR'],
            ['product_id' => 'PRD-BB-0007', 'name' => 'Pigment Oxide Red', 'category' => 'Bahan Baku', 'base_cost' => 22000, 'currency' => 'IDR'],
            ['product_id' => 'PRD-BB-0008', 'name' => 'Pigment Carbon Black', 'category' => 'Bahan Baku', 'base_cost' => 19000, 'currency' => 'IDR'],
            ['product_id' => 'PRD-BB-0009', 'name' => 'Pigment Yellow Oxide', 'category' => 'Bahan Baku', 'base_cost' => 15500, 'currency' => 'IDR'],
            ['product_id' => 'PRD-BB-0010', 'name' => 'Alkyd Resin Medium', 'category' => 'Bahan Baku', 'base_cost' => 9800, 'currency' => 'IDR'],
            ['product_id' => 'PRD-BB-0011', 'name' => 'Acrylic Emulsion', 'category' => 'Bahan Baku', 'base_cost' => 11200, 'currency' => 'IDR'],
            ['product_id' => 'PRD-BB-0012', 'name' => 'Mineral Spirit', 'category' => 'Bahan Baku', 'base_cost' => 6500, 'currency' => 'IDR'],
            ['product_id' => 'PRD-BN-0001', 'name' => 'Thinner A Special', 'category' => 'Penolong', 'base_cost' => 12500, 'currency' => 'IDR'],
            ['product_id' => 'PRD-BN-0002', 'name' => 'Defoamer AF-200', 'category' => 'Penolong', 'base_cost' => 35000, 'currency' => 'IDR'],
            ['product_id' => 'PRD-BN-0003', 'name' => 'Dispersing Agent', 'category' => 'Penolong', 'base_cost' => 28000, 'currency' => 'IDR'],
            ['product_id' => 'PRD-BN-0004', 'name' => 'Anti-Rust Agent', 'category' => 'Penolong', 'base_cost' => 22000, 'currency' => 'IDR'],
            ['product_id' => 'PRD-BN-0005', 'name' => 'Wax Emulsion', 'category' => 'Penolong', 'base_cost' => 18000, 'currency' => 'IDR'],
            ['product_id' => 'PRD-FG-0001', 'name' => 'Wall Paint White 20L', 'category' => 'Finished Goods', 'base_cost' => 185000, 'selling_price' => 275000, 'currency' => 'IDR'],
            ['product_id' => 'PRD-FG-0002', 'name' => 'Wall Paint Cream 10L', 'category' => 'Finished Goods', 'base_cost' => 110000, 'selling_price' => 165000, 'currency' => 'IDR'],
            ['product_id' => 'PRD-FG-0003', 'name' => 'Primer Grey 5L', 'category' => 'Finished Goods', 'base_cost' => 68000, 'selling_price' => 98000, 'currency' => 'IDR'],
            ['product_id' => 'PRD-FG-0004', 'name' => 'Top Coat Clear 15L', 'category' => 'Finished Goods', 'base_cost' => 145000, 'selling_price' => 215000, 'currency' => 'IDR'],
            ['product_id' => 'PRD-FG-0005', 'name' => 'Cat Ekonomis 5L', 'category' => 'Finished Goods', 'base_cost' => 42000, 'selling_price' => 62000, 'currency' => 'IDR'],
        ];

        foreach ($products as $p) {
            $sp = $p['selling_price'] ?? $p['base_cost'] * rand(120, 180) / 100;
            $margin = $p['base_cost'] > 0 ? round(($sp - $p['base_cost']) / $p['base_cost'] * 100, 1) : 0;
            $this->store->create([
                'product_id' => $p['product_id'],
                'name' => $p['name'],
                'category' => $p['category'],
                'selling_price' => round($sp),
                'base_cost' => $p['base_cost'],
                'margin' => $margin,
                'currency' => $p['currency'],
            ]);
        }
    }

    public function index()
    {
        return view('material-management.product-price-info');
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
        if ($request->filled('filter_category') && $request->filter_category !== 'all') {
            $data = array_filter($data, fn($i) => ($i['category'] ?? '') === $request->filter_category);
        }
        if ($request->filled('filter_margin_min')) {
            $data = array_filter($data, fn($i) => ($i['margin'] ?? 0) >= floatval($request->filter_margin_min));
        }
        if ($request->filled('filter_margin_max')) {
            $data = array_filter($data, fn($i) => ($i['margin'] ?? 0) <= floatval($request->filter_margin_max));
        }

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('category_badge', function ($r) {
                return match($r['category']) {
                    'Bahan Baku' => '<span class="badge bg-primary"><i class="bi bi-box me-1"></i>Bahan Baku</span>',
                    'Penolong' => '<span class="badge bg-info"><i class="bi bi-box-seam me-1"></i>Penolong</span>',
                    'Finished Goods' => '<span class="badge bg-success"><i class="bi bi-box-check me-1"></i>Finished Goods</span>',
                    default => '<span class="badge bg-secondary">'.$r['category'].'</span>',
                };
            })
            ->addColumn('selling_fmt', fn($r) => 'Rp '.number_format($r['selling_price'], 0, ',', '.'))
            ->addColumn('cost_fmt', fn($r) => 'Rp '.number_format($r['base_cost'], 0, ',', '.'))
            ->addColumn('margin_badge', function ($r) {
                $m = $r['margin'] ?? 0;
                if ($m >= 50) $cls = 'bg-success';
                elseif ($m >= 30) $cls = 'bg-info';
                elseif ($m >= 15) $cls = 'bg-warning text-dark';
                else $cls = 'bg-danger';
                return '<span class="badge '.$cls.' fs-6">'.$m.'%</span>';
            })
            ->rawColumns(['category_badge','selling_fmt','cost_fmt','margin_badge'])
            ->make(true);
    }
}
