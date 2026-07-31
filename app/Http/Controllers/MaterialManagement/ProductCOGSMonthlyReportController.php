<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ProductCOGSMonthlyReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('product-cogs-monthly');
        $this->initDummyData();
        View::share('activeMenu', 'material-management');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $products = [
            ['product_id' => 'PRD-FG-0001', 'name' => 'Wall Paint White 20L', 'base_cogs' => 185000],
            ['product_id' => 'PRD-FG-0002', 'name' => 'Wall Paint Cream 10L', 'base_cogs' => 110000],
            ['product_id' => 'PRD-FG-0003', 'name' => 'Primer Grey 5L', 'base_cogs' => 68000],
            ['product_id' => 'PRD-FG-0004', 'name' => 'Top Coat Clear 15L', 'base_cogs' => 145000],
            ['product_id' => 'PRD-FG-0005', 'name' => 'Cat Ekonomis 5L', 'base_cogs' => 42000],
            ['product_id' => 'PRD-BB-0001', 'name' => 'Resin Polyester White', 'base_cogs' => 8500],
            ['product_id' => 'PRD-BB-0004', 'name' => 'Titanium Dioxide R-706', 'base_cogs' => 17500],
        ];

        $months = ['2026-01','2026-02','2026-03','2026-04','2026-05','2026-06','2026-07'];

        foreach ($months as $m) {
            foreach ($products as $p) {
                $variation = rand(85, 120) / 100;
                $avgCogs = round($p['base_cogs'] * $variation);
                $qty = rand(50, 400);
                $this->store->create([
                    'period' => $m,
                    'product_id' => $p['product_id'],
                    'name' => $p['name'],
                    'avg_cogs_unit' => $avgCogs,
                    'total_manufactured_qty' => $qty,
                    'total_cogs_valuation' => $avgCogs * $qty,
                ]);
            }
        }
    }

    public function index()
    {
        return view('material-management.product-cogs-monthly-report');
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
        if ($request->filled('filter_month') && $request->filter_month !== 'all') {
            $data = array_filter($data, fn($i) => ($i['period'] ?? '') === $request->filter_month);
        }

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('period_fmt', fn($r) => \Carbon\Carbon::parse(($r['period'] ?? '2026-01').'-01')->format('F Y'))
            ->addColumn('avg_cogs_fmt', fn($r) => 'Rp '.number_format($r['avg_cogs_unit'] ?? 0, 0, ',', '.'))
            ->addColumn('qty_fmt', fn($r) => number_format($r['total_manufactured_qty'] ?? 0, 0, ',', '.'))
            ->addColumn('valuation_fmt', fn($r) => 'Rp '.number_format($r['total_cogs_valuation'] ?? 0, 0, ',', '.'))
            ->rawColumns(['period_fmt','avg_cogs_fmt','qty_fmt','valuation_fmt'])
            ->make(true);
    }
}
