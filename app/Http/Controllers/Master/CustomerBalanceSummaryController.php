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
        return view('master.customer-balance-summary.index');
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

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('beginning_balance_fmt', fn($r) => 'Rp ' . number_format((int)($r['beginning_balance'] ?? 0), 0, ',', '.'))
            ->addColumn('total_invoice_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_invoice'] ?? 0), 0, ',', '.'))
            ->addColumn('total_payment_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_payment'] ?? 0), 0, ',', '.'))
            ->addColumn('total_return_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_return'] ?? 0), 0, ',', '.'))
            ->addColumn('ending_balance_fmt', fn($r) => 'Rp ' . number_format((int)($r['ending_balance'] ?? 0), 0, ',', '.'))
            ->addColumn('credit_limit_fmt', fn($r) => 'Rp ' . number_format((int)($r['credit_limit'] ?? 0), 0, ',', '.'))
            ->addColumn('available_credit_fmt', fn($r) => 'Rp ' . number_format((int)($r['available_credit'] ?? 0), 0, ',', '.'))
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-detail" data-id="' . $row['customer_id'] . '"><i class="bi bi-eye"></i></button>
                </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
