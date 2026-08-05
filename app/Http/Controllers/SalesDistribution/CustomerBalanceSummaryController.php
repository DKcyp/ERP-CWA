<?php

namespace App\Http\Controllers\SalesDistribution;

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
        $this->store = new DummyStore('customer-balance-summary');
        $this->initDummyData();
        View::share('activeMenu', 'customer-balance-summary');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $customers = [
            ['id' => 'CUST-001', 'name' => 'PT Maju Jaya Abadi', 'currency' => 'IDR', 'credit_limit' => 150000000],
            ['id' => 'CUST-002', 'name' => 'CV Berkah Mulia', 'currency' => 'IDR', 'credit_limit' => 80000000],
            ['id' => 'CUST-003', 'name' => 'PT Sinar Terang Perkasa', 'currency' => 'IDR', 'credit_limit' => 200000000],
            ['id' => 'CUST-004', 'name' => 'CV Pelangi Cat Indonesia', 'currency' => 'IDR', 'credit_limit' => 120000000],
            ['id' => 'CUST-005', 'name' => 'PT Sentosa Paint', 'currency' => 'IDR', 'credit_limit' => 180000000],
            ['id' => 'CUST-006', 'name' => 'CV Abadi Jaya', 'currency' => 'IDR', 'credit_limit' => 60000000],
            ['id' => 'CUST-007', 'name' => 'PT Sejahtera Coatings', 'currency' => 'IDR', 'credit_limit' => 250000000],
            ['id' => 'CUST-008', 'name' => 'CV Mitra Warna', 'currency' => 'IDR', 'credit_limit' => 90000000],
            ['id' => 'CUST-009', 'name' => 'PT Harapan Cat Dunia', 'currency' => 'IDR', 'credit_limit' => 170000000],
            ['id' => 'CUST-010', 'name' => 'CV Dua Putra', 'currency' => 'IDR', 'credit_limit' => 70000000],
            ['id' => 'CUST-011', 'name' => 'PT Cemerlang Paint', 'currency' => 'IDR', 'credit_limit' => 130000000],
            ['id' => 'CUST-012', 'name' => 'CV Sumber Rejeki', 'currency' => 'IDR', 'credit_limit' => 55000000],
            ['id' => 'CUST-013', 'name' => 'PT Bintang Coatings', 'currency' => 'USD', 'credit_limit' => 25000],
            ['id' => 'CUST-014', 'name' => 'PT Dewa Paint', 'currency' => 'USD', 'credit_limit' => 18000],
            ['id' => 'CUST-015', 'name' => 'CV Anugerah Cat', 'currency' => 'IDR', 'credit_limit' => 100000000],
            ['id' => 'CUST-016', 'name' => 'PT Prima Warna Sejati', 'currency' => 'IDR', 'credit_limit' => 140000000],
            ['id' => 'CUST-017', 'name' => 'CV Lancar Jaya', 'currency' => 'IDR', 'credit_limit' => 65000000],
            ['id' => 'CUST-018', 'name' => 'PT Multiwarna Abadi', 'currency' => 'IDR', 'credit_limit' => 190000000],
            ['id' => 'CUST-019', 'name' => 'CV Sinar Mas Paint', 'currency' => 'USD', 'credit_limit' => 30000],
            ['id' => 'CUST-020', 'name' => 'PT Gemilang Cat Pro', 'currency' => 'IDR', 'credit_limit' => 110000000],
            ['id' => 'CUST-021', 'name' => 'CV Putra Surya', 'currency' => 'IDR', 'credit_limit' => 45000000],
            ['id' => 'CUST-022', 'name' => 'PT Wahana Cat Nusantara', 'currency' => 'IDR', 'credit_limit' => 220000000],
            ['id' => 'CUST-023', 'name' => 'CV Jaya Bersama', 'currency' => 'IDR', 'credit_limit' => 75000000],
            ['id' => 'CUST-024', 'name' => 'PT Catku Indonesia', 'currency' => 'IDR', 'credit_limit' => 160000000],
            ['id' => 'CUST-025', 'name' => 'CV Karya Cat', 'currency' => 'IDR', 'credit_limit' => 85000000],
        ];

        foreach ($customers as $c) {
            $isUsd = $c['currency'] === 'USD';
            $begBal = $isUsd ? rand(1000, 12000) : rand(5000000, 80000000);
            $totalInvoice = $isUsd ? rand(2000, 20000) : rand(10000000, 120000000);
            $totalPayment = $isUsd ? (int)($totalInvoice * (rand(60, 100) / 100)) : (int)($totalInvoice * (rand(60, 100) / 100));
            $totalReturn = $isUsd ? rand(0, 3000) : rand(0, 15000000);
            $endBal = $begBal + $totalInvoice - $totalPayment - $totalReturn;

            $this->store->create([
                'customer_id' => $c['id'],
                'customer_name' => $c['name'],
                'currency' => $c['currency'],
                'beginning_balance' => $begBal,
                'total_invoice' => $totalInvoice,
                'total_payment' => $totalPayment,
                'total_return' => $totalReturn,
                'ending_balance' => $endBal,
                'credit_limit' => $c['credit_limit'],
                'available_credit' => $c['credit_limit'] - $endBal,
            ]);
        }
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
                stripos($i['customer_id'] ?? '', $q) !== false ||
                stripos($i['customer_name'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_currency') && $request->filter_currency !== 'all')
            $data = array_filter($data, fn($i) => ($i['currency'] ?? '') === $request->filter_currency);

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('beginning_balance_fmt', function ($r) {
                $sym = $r['currency'] === 'USD' ? '$' : 'Rp';
                return $sym . ' ' . number_format($r['beginning_balance'] ?? 0, $r['currency'] === 'USD' ? 2 : 0, ',', '.');
            })
            ->addColumn('total_invoice_fmt', function ($r) {
                $sym = $r['currency'] === 'USD' ? '$' : 'Rp';
                return $sym . ' ' . number_format($r['total_invoice'] ?? 0, $r['currency'] === 'USD' ? 2 : 0, ',', '.');
            })
            ->addColumn('total_payment_fmt', function ($r) {
                $sym = $r['currency'] === 'USD' ? '$' : 'Rp';
                return $sym . ' ' . number_format($r['total_payment'] ?? 0, $r['currency'] === 'USD' ? 2 : 0, ',', '.');
            })
            ->addColumn('total_return_fmt', function ($r) {
                $sym = $r['currency'] === 'USD' ? '$' : 'Rp';
                return $sym . ' ' . number_format($r['total_return'] ?? 0, $r['currency'] === 'USD' ? 2 : 0, ',', '.');
            })
            ->addColumn('ending_balance_fmt', function ($r) {
                $sym = $r['currency'] === 'USD' ? '$' : 'Rp';
                return $sym . ' ' . number_format($r['ending_balance'] ?? 0, $r['currency'] === 'USD' ? 2 : 0, ',', '.');
            })
            ->addColumn('credit_limit_fmt', function ($r) {
                $sym = $r['currency'] === 'USD' ? '$' : 'Rp';
                return $sym . ' ' . number_format($r['credit_limit'] ?? 0, $r['currency'] === 'USD' ? 2 : 0, ',', '.');
            })
            ->addColumn('available_credit_fmt', function ($r) {
                $sym = $r['currency'] === 'USD' ? '$' : 'Rp';
                $val = $r['available_credit'] ?? 0;
                $class = $val < 0 ? 'text-danger fw-bold' : ($val < ($r['credit_limit'] ?? 0) * 0.1 ? 'text-warning fw-bold' : 'text-success');
                return '<span class="'.$class.'">'.$sym.' '.number_format($val, $r['currency'] === 'USD' ? 2 : 0, ',', '.').'</span>';
            })
            ->rawColumns(['beginning_balance_fmt','total_invoice_fmt','total_payment_fmt','total_return_fmt','ending_balance_fmt','credit_limit_fmt','available_credit_fmt'])
            ->make(true);
    }
}
