<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ProductStockDailySummaryController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('product-stock-daily');
        $this->initDummyData();
        View::share('activeMenu', 'material-management');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $products = [
            ['product_id' => 'PRD-BB-0001', 'name' => 'Resin Polyester White', 'uom' => 'Kg'],
            ['product_id' => 'PRD-BB-0002', 'name' => 'Resin Epoxy Clear', 'uom' => 'Kg'],
            ['product_id' => 'PRD-BB-0003', 'name' => 'Talc Powder 400 Mesh', 'uom' => 'Kg'],
            ['product_id' => 'PRD-BB-0004', 'name' => 'Titanium Dioxide R-706', 'uom' => 'Kg'],
            ['product_id' => 'PRD-BB-0005', 'name' => 'Calcium Carbonate', 'uom' => 'Kg'],
            ['product_id' => 'PRD-BN-0001', 'name' => 'Thinner A Special', 'uom' => 'L'],
            ['product_id' => 'PRD-BN-0002', 'name' => 'Defoamer AF-200', 'uom' => 'Kg'],
            ['product_id' => 'PRD-FG-0001', 'name' => 'Wall Paint White 20L', 'uom' => 'Pcs'],
            ['product_id' => 'PRD-FG-0002', 'name' => 'Wall Paint Cream 10L', 'uom' => 'Pcs'],
            ['product_id' => 'PRD-FG-0003', 'name' => 'Primer Grey 5L', 'uom' => 'Pcs'],
        ];

        $stock = [];
        foreach ($products as $p) { $stock[$p['product_id']] = rand(500, 3000); }

        for ($d = 0; $d < 14; $d++) {
            $date = date('Y-m-d', strtotime("2026-07-17 +{$d} days"));
            foreach ($products as $p) {
                $pid = $p['product_id'];
                $initial = $stock[$pid];
                $inQty = rand(50, 400);
                $outQty = rand(20, 350);
                $final = $initial + $inQty - $outQty;
                $stock[$pid] = max(10, $final);
                $this->store->create([
                    'date' => $date,
                    'product_id' => $pid,
                    'name' => $p['name'],
                    'initial_stock' => $initial,
                    'in_qty' => $inQty,
                    'out_qty' => $outQty,
                    'final_stock' => max(10, $final),
                    'uom' => $p['uom'],
                ]);
            }
        }
    }

    public function index()
    {
        return view('material-management.product-stock-daily-summary');
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
                stripos($i['name'] ?? '', $q) !== false
            );
        }

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('initial_fmt', fn($r) => number_format($r['initial_stock'], 0, ',', '.'))
            ->addColumn('in_fmt', fn($r) => '<span class="text-success fw-semibold">+'.number_format($r['in_qty'], 0, ',', '.').'</span>')
            ->addColumn('out_fmt', fn($r) => '<span class="text-danger fw-semibold">-'.number_format($r['out_qty'], 0, ',', '.').'</span>')
            ->addColumn('final_fmt', function ($r) {
                $diff = $r['final_stock'] - $r['initial_stock'];
                $cls = $diff >= 0 ? 'text-success' : 'text-danger';
                $arrow = $diff >= 0 ? '▲' : '▼';
                return '<span class="'.$cls.' fw-bold">'.number_format($r['final_stock'], 0, ',', '.').' <small>'.$arrow.'</small></span>';
            })
            ->rawColumns(['date_fmt','initial_fmt','in_fmt','out_fmt','final_fmt'])
            ->make(true);
    }
}
