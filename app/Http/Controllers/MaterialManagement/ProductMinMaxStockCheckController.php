<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ProductMinMaxStockCheckController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('material-stock');
        $this->initMinMaxData();
        View::share('activeMenu', 'material-management');
    }

    protected function initMinMaxData(): void
    {
        $data = $this->store->all();
        if (empty($data)) return;
        $has = collect($data)->first(fn($i) => isset($i['min_stock']));
        if ($has) return;

        foreach ($data as $item) {
            $cur = $item['current_stock'] ?? 0;
            $min = max(10, intdiv($cur, 5));
            $max = $cur + rand(100, 500);
            $safety = max(5, intdiv($min, 2));
            $reorder = $max - $cur;
            $this->store->update($item['id'], [
                'min_stock' => $min,
                'max_stock' => $max,
                'safety_stock' => $safety,
                'reorder_qty' => $reorder,
            ]);
        }
    }

    public function index()
    {
        return view('material-management.product-min-max-stock-check');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        $enriched = array_map(function ($r) {
            $cur = $r['current_stock'] ?? 0;
            $min = $r['min_stock'] ?? 0;
            $max = $r['max_stock'] ?? 0;
            if ($cur < $min) $status = 'Below Min';
            elseif ($cur > $max) $status = 'Over Max';
            else $status = 'Normal';
            $r['status'] = $status;
            return $r;
        }, $data);

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $enriched = array_values(array_filter($enriched, fn($i) =>
                stripos($i['product_id'] ?? '', $q) !== false ||
                stripos($i['name'] ?? '', $q) !== false
            ));
        }
        if ($request->filled('filter_warehouse') && $request->filter_warehouse !== 'all') {
            $enriched = array_values(array_filter($enriched, fn($i) => ($i['warehouse'] ?? '') === $request->filter_warehouse));
        }
        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $enriched = array_values(array_filter($enriched, fn($i) => $i['status'] === $request->filter_status));
        }

        return DataTables::of($enriched)
            ->addIndexColumn()
            ->addColumn('current_fmt', fn($r) => number_format($r['current_stock'] ?? 0, 0, ',', '.'))
            ->addColumn('min_fmt', fn($r) => number_format($r['min_stock'] ?? 0, 0, ',', '.'))
            ->addColumn('max_fmt', fn($r) => number_format($r['max_stock'] ?? 0, 0, ',', '.'))
            ->addColumn('safety_fmt', fn($r) => number_format($r['safety_stock'] ?? 0, 0, ',', '.'))
            ->addColumn('reorder_fmt', fn($r) => number_format($r['reorder_qty'] ?? 0, 0, ',', '.'))
            ->addColumn('status_badge', function ($r) {
                return match($r['status']) {
                    'Below Min' => '<span class="badge bg-danger"><i class="bi bi-exclamation-triangle me-1"></i>Warning Below Min</span>',
                    'Over Max' => '<span class="badge bg-warning text-dark"><i class="bi bi-arrow-up-circle me-1"></i>Over Max</span>',
                    default => '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Normal</span>',
                };
            })
            ->rawColumns(['current_fmt','min_fmt','max_fmt','safety_fmt','reorder_fmt','status_badge'])
            ->make(true);
    }
}
