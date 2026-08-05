<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class CustomerBalanceSummaryController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('customer-balances');
        View::share('activeMenu', 'customer-balance-summary');
    }

    public function index()
    {
        return view('Sales-distribution.customer-balance-summary');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['customer_id'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_currency') && $request->filter_currency !== 'all')
            $data = array_filter($data, fn($i) => ($i['currency'] ?? 'IDR') === $request->filter_currency);

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('currency_badge', fn($r) => '<span class="badge bg-secondary">'.($r['currency'] ?? 'IDR').'</span>')
            ->addColumn('beginning_balance_fmt', fn($r) => 'Rp ' . number_format((int)($r['beginning_balance'] ?? 0), 0, ',', '.'))
            ->addColumn('total_invoice_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_invoice'] ?? 0), 0, ',', '.'))
            ->addColumn('total_payment_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_payment'] ?? 0), 0, ',', '.'))
            ->addColumn('total_return_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_return'] ?? 0), 0, ',', '.'))
            ->addColumn('ending_balance_fmt', fn($r) => 'Rp ' . number_format((int)($r['ending_balance'] ?? 0), 0, ',', '.'))
            ->addColumn('credit_limit_fmt', fn($r) => 'Rp ' . number_format((int)($r['credit_limit'] ?? 0), 0, ',', '.'))
            ->addColumn('available_credit_fmt', function ($r) {
                $val = (int)($r['available_credit'] ?? 0);
                $class = $val < 0 ? 'text-danger fw-bold' : ($val < ($r['credit_limit'] ?? 0) * 0.1 ? 'text-warning fw-bold' : 'text-success');
                return '<span class="'.$class.'">Rp '.number_format($val, 0, ',', '.').'</span>';
            })
            ->rawColumns(['currency_badge','available_credit_fmt'])
            ->make(true);
    }
}
