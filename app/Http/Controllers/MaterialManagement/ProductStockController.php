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
        View::share('activeMenu', 'material-management');
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
