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
        View::share('activeMenu', 'material-management');
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
