<?php

namespace App\Http\Controllers\TransitArea;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ArPerCustomerReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('ar_per_customer_report');
        View::share('activeMenu', 'ar-per-customer-report');
    }

    public function index()
    {
        return view('transit-area.ar-per-customer-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['cust_id'] ?? '', $q) !== false ||
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['area'] ?? '', $q) !== false ||
                stripos($i['warehouse'] ?? '', $q) !== false ||
                stripos($i['salesman'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_warehouse')) {
            $data = array_filter($data, fn($i) => ($i['warehouse'] ?? '') === $request->filter_warehouse);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('saldo_awal_fmt', fn($r) => 'Rp ' . number_format((int)($r['saldo_awal'] ?? 0), 0, ',', '.'))
            ->addColumn('penjualan_fmt', fn($r) => 'Rp ' . number_format((int)($r['penjualan'] ?? 0), 0, ',', '.'))
            ->addColumn('po_closing_fmt', fn($r) => 'Rp ' . number_format((int)($r['po_closing'] ?? 0), 0, ',', '.'))
            ->addColumn('bank_fmt', fn($r) => 'Rp ' . number_format((int)($r['bank'] ?? 0), 0, ',', '.'))
            ->addColumn('cash_fmt', fn($r) => 'Rp ' . number_format((int)($r['cash'] ?? 0), 0, ',', '.'))
            ->addColumn('discount_fmt', fn($r) => 'Rp ' . number_format((int)($r['discount'] ?? 0), 0, ',', '.'))
            ->addColumn('lain_lain_fmt', fn($r) => 'Rp ' . number_format((int)($r['lain_lain'] ?? 0), 0, ',', '.'))
            ->addColumn('retur_fmt', fn($r) => 'Rp ' . number_format((int)($r['retur'] ?? 0), 0, ',', '.'))
            ->addColumn('saldo_akhir_fmt', fn($r) => 'Rp ' . number_format((int)($r['saldo_akhir'] ?? 0), 0, ',', '.'))
            ->addColumn('sisa_piutang_fmt', fn($r) => 'Rp ' . number_format((int)($r['sisa_piutang'] ?? 0), 0, ',', '.'))
            ->addColumn('selisih_fmt', fn($r) => 'Rp ' . number_format((int)($r['selisih'] ?? 0), 0, ',', '.'))
            ->addColumn('lt45_fmt', fn($r) => 'Rp ' . number_format((int)($r['lt45'] ?? 0), 0, ',', '.'))
            ->addColumn('gt45_fmt', fn($r) => 'Rp ' . number_format((int)($r['gt45'] ?? 0), 0, ',', '.'))
            ->addColumn('gt90_fmt', fn($r) => 'Rp ' . number_format((int)($r['gt90'] ?? 0), 0, ',', '.'))
            ->addColumn('gt120_fmt', fn($r) => 'Rp ' . number_format((int)($r['gt120'] ?? 0), 0, ',', '.'))
            ->make(true);
    }

    public function show($id)
    {
        $d = $this->store->find($id);
        return $d ? response()->json(['success' => true, 'data' => $d]) : response()->json(['message' => 'Data tidak ditemukan.'], 404);
    }
}