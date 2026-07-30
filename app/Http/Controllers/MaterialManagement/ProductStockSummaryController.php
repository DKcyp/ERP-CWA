<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ProductStockSummaryController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('material-stock');
        $this->initDummyValuation();
        View::share('activeMenu', 'material-management');
    }

    protected function initDummyValuation(): void
    {
        $data = $this->store->all();
        if (empty($data)) return;
        $hasPrice = collect($data)->first(fn($i) => isset($i['unit_price']));
        if ($hasPrice) return;

        $priceRanges = [
            'Bahan Baku' => [3500, 18000],
            'Penolong' => [8000, 45000],
            'WIP' => [12000, 55000],
            'Finished Goods' => [85000, 320000],
        ];
        foreach ($data as $item) {
            $cat = $item['category'] ?? 'Bahan Baku';
            [$min, $max] = $priceRanges[$cat] ?? [5000, 20000];
            $this->store->update($item['id'], ['unit_price' => rand($min, $max)]);
        }
    }

    public function index()
    {
        $data = $this->store->all();
        $totalValuation = 0;
        $grouped = collect($data)->groupBy(fn($i) => $i['product_id']);
        foreach ($grouped as $items) {
            $first = $items->first();
            $totalQty = $items->sum('current_stock');
            $totalValuation += $totalQty * ($first['unit_price'] ?? 0);
        }
        $totalItems = count($grouped);
        $totalStock = collect($data)->sum('current_stock');

        return view('material-management.product-stock-summary', compact('totalItems', 'totalStock', 'totalValuation'));
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        $grouped = collect($data)->groupBy(fn($i) => $i['product_id']);

        $summary = $grouped->map(function ($items) {
            $first = $items->first();
            $totalQty = $items->sum('current_stock');
            $totalReserved = $items->sum('reserved_stock');
            $unitPrice = $first['unit_price'] ?? 0;
            $valuation = $totalQty * $unitPrice;
            return [
                'product_id' => $first['product_id'],
                'name' => $first['name'],
                'category' => $first['category'],
                'total_warehouses' => $items->count(),
                'total_qty' => $totalQty,
                'total_reserved' => $totalReserved,
                'unit_price' => $unitPrice,
                'valuation' => $valuation,
                'uom' => $first['uom'],
            ];
        })->values()->toArray();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $summary = array_values(array_filter($summary, fn($i) =>
                stripos($i['product_id'], $q) !== false ||
                stripos($i['name'], $q) !== false
            ));
        }
        if ($request->filled('filter_category') && $request->filter_category !== 'all') {
            $summary = array_values(array_filter($summary, fn($i) => $i['category'] === $request->filter_category));
        }

        return DataTables::of($summary)
            ->addIndexColumn()
            ->addColumn('category_badge', function ($r) {
                return match($r['category']) {
                    'Bahan Baku' => '<span class="badge bg-primary"><i class="bi bi-box me-1"></i>Bahan Baku</span>',
                    'Penolong' => '<span class="badge bg-info"><i class="bi bi-box-seam me-1"></i>Penolong</span>',
                    'WIP' => '<span class="badge bg-warning text-dark"><i class="bi bi-gear me-1"></i>WIP</span>',
                    'Finished Goods' => '<span class="badge bg-success"><i class="bi bi-box-check me-1"></i>Finished Goods</span>',
                    default => '<span class="badge bg-secondary">'.$r['category'].'</span>',
                };
            })
            ->addColumn('total_qty_fmt', fn($r) => number_format($r['total_qty'], 0, ',', '.'))
            ->addColumn('total_reserved_fmt', fn($r) => number_format($r['total_reserved'], 0, ',', '.'))
            ->addColumn('unit_price_fmt', fn($r) => 'Rp '.number_format($r['unit_price'], 0, ',', '.'))
            ->addColumn('valuation_fmt', fn($r) => 'Rp '.number_format($r['valuation'], 0, ',', '.'))
            ->rawColumns(['category_badge','total_qty_fmt','total_reserved_fmt','unit_price_fmt','valuation_fmt'])
            ->make(true);
    }
}
