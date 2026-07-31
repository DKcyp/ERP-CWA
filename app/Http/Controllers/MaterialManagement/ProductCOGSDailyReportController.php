<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ProductCOGSDailyReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('product-cogs-daily');
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
        ];

        for ($d = 0; $d < 30; $d++) {
            $date = date('Y-m-d', strtotime("2026-07-01 +{$d} days"));
            $batchCount = rand(2, 4);
            for ($b = 0; $b < $batchCount; $b++) {
                $p = $products[array_rand($products)];
                $variation = rand(88, 115) / 100;
                $cogsUnit = round($p['base_cogs'] * $variation);
                $qty = rand(20, 150);
                $this->store->create([
                    'date' => $date,
                    'production_ref' => 'PRD-LST-'.date('ymd', strtotime($date)).'-'.str_pad($b + 1, 3, '0', STR_PAD_LEFT),
                    'product_id' => $p['product_id'],
                    'name' => $p['name'],
                    'daily_cogs_unit' => $cogsUnit,
                    'batch_qty' => $qty,
                    'total_valuation' => $cogsUnit * $qty,
                ]);
            }
        }
    }

    public function index()
    {
        return view('material-management.product-cogs-daily-report');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_date_from')) $data = array_filter($data, fn($i) => ($i['date'] ?? '') >= $request->filter_date_from);
        if ($request->filled('filter_date_to')) $data = array_filter($data, fn($i) => ($i['date'] ?? '') <= $request->filter_date_to);
        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['product_id'] ?? '', $q) !== false ||
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['production_ref'] ?? '', $q) !== false
            );
        }

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('cogs_fmt', fn($r) => 'Rp '.number_format($r['daily_cogs_unit'] ?? 0, 0, ',', '.'))
            ->addColumn('qty_fmt', fn($r) => number_format($r['batch_qty'] ?? 0, 0, ',', '.'))
            ->addColumn('valuation_fmt', fn($r) => 'Rp '.number_format($r['total_valuation'] ?? 0, 0, ',', '.'))
            ->rawColumns(['date_fmt','cogs_fmt','qty_fmt','valuation_fmt'])
            ->make(true);
    }
}
