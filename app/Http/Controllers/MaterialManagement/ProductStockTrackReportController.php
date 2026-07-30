<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ProductStockTrackReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('product-stock-track');
        $this->initDummyData();
        View::share('activeMenu', 'material-management');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $products = [
            ['product_id' => 'PRD-BB-0001', 'name' => 'Resin Polyester White'],
            ['product_id' => 'PRD-BB-0004', 'name' => 'Titanium Dioxide R-706'],
            ['product_id' => 'PRD-BB-0007', 'name' => 'Pigment Oxide Red'],
            ['product_id' => 'PRD-BN-0001', 'name' => 'Thinner A Special'],
            ['product_id' => 'PRD-FG-0001', 'name' => 'Wall Paint White 20L'],
            ['product_id' => 'PRD-FG-0002', 'name' => 'Wall Paint Cream 10L'],
            ['product_id' => 'PRD-FG-0003', 'name' => 'Primer Grey 5L'],
        ];

        $types = [
            ['type' => 'Purchase Receipt', 'code' => 'PR', 'in' => true],
            ['type' => 'Production Output', 'code' => 'PO', 'in' => true],
            ['type' => 'Stock Adjustment (+)', 'code' => 'SA', 'in' => true],
            ['type' => 'Sales Delivery', 'code' => 'SD', 'in' => false],
            ['type' => 'Production Usage', 'code' => 'PU', 'in' => false],
            ['type' => 'Stock Adjustment (-)', 'code' => 'SN', 'in' => false],
            ['type' => 'Transfer In', 'code' => 'TI', 'in' => true],
            ['type' => 'Transfer Out', 'code' => 'TO', 'in' => false],
        ];

        $users = ['Ahmad Operator','Dewi QC','Rudi Staff','Siti Admin','Bambang Gudang','Lina Produksi'];

        $balance = [];
        foreach ($products as $p) { $balance[$p['product_id']] = rand(200, 1500); }

        for ($d = 0; $d < 21; $d++) {
            $date = date('Y-m-d', strtotime("2026-07-10 +{$d} days"));
            $txCount = rand(2, 5);
            for ($t = 0; $t < $txCount; $t++) {
                $p = $products[array_rand($products)];
                $tp = $types[array_rand($types)];
                $qty = rand(10, 200);
                $pid = $p['product_id'];

                if ($tp['in']) {
                    $in = $qty; $out = 0;
                } else {
                    $in = 0; $out = min($qty, max(1, $balance[$pid] - 10));
                }
                $balance[$pid] = $balance[$pid] + $in - $out;

                $refNo = $tp['code'].'-'.date('Ymd', strtotime($date)).'-'.str_pad($d * 5 + $t + 1, 3, '0', STR_PAD_LEFT);

                $this->store->create([
                    'trans_date' => $date,
                    'product_id' => $pid,
                    'name' => $p['name'],
                    'ref_doc_no' => $refNo,
                    'transaction_type' => $tp['type'],
                    'in_qty' => $in,
                    'out_qty' => $out,
                    'balance_qty' => $balance[$pid],
                    'user_id' => $users[array_rand($users)],
                ]);
            }
        }
    }

    public function index()
    {
        return view('material-management.product-stock-track-report');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_date_from')) $data = array_filter($data, fn($i) => ($i['trans_date'] ?? '') >= $request->filter_date_from);
        if ($request->filled('filter_date_to')) $data = array_filter($data, fn($i) => ($i['trans_date'] ?? '') <= $request->filter_date_to);
        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['product_id'] ?? '', $q) !== false ||
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['ref_doc_no'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_type') && $request->filter_type !== 'all') {
            $data = array_filter($data, fn($i) => ($i['transaction_type'] ?? '') === $request->filter_type);
        }

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['trans_date'])->format('d/m/Y'))
            ->addColumn('in_fmt', fn($r) => $r['in_qty'] > 0 ? '<span class="text-success fw-semibold">+'.number_format($r['in_qty'], 0, ',', '.').'</span>' : '<span class="text-muted">-</span>')
            ->addColumn('out_fmt', fn($r) => $r['out_qty'] > 0 ? '<span class="text-danger fw-semibold">-'.number_format($r['out_qty'], 0, ',', '.').'</span>' : '<span class="text-muted">-</span>')
            ->addColumn('balance_fmt', fn($r) => number_format($r['balance_qty'], 0, ',', '.'))
            ->addColumn('type_badge', function ($r) {
                $t = $r['transaction_type'] ?? '';
                return match(true) {
                    str_starts_with($t, 'Purchase') => '<span class="badge bg-primary"><i class="bi bi-truck me-1"></i>'.$t.'</span>',
                    str_starts_with($t, 'Production') => '<span class="badge bg-warning text-dark"><i class="bi bi-gear me-1"></i>'.$t.'</span>',
                    str_starts_with($t, 'Sales') => '<span class="badge bg-danger"><i class="bi bi-cart-dash me-1"></i>'.$t.'</span>',
                    str_starts_with($t, 'Transfer') => '<span class="badge bg-info"><i class="bi bi-arrow-left-right me-1"></i>'.$t.'</span>',
                    str_starts_with($t, 'Stock') => '<span class="badge bg-secondary"><i class="bi bi-sliders me-1"></i>'.$t.'</span>',
                    default => '<span class="badge bg-secondary">'.$t.'</span>',
                };
            })
            ->rawColumns(['date_fmt','in_fmt','out_fmt','balance_fmt','type_badge'])
            ->make(true);
    }
}
