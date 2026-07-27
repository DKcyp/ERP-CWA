<?php

namespace App\Http\Controllers\TransitArea;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class InvoiceCustomerArListReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('invoice_customer_ar_list_report');
        View::share('activeMenu', 'invoice-customer-ar-list-report');
    }

    public function index()
    {
        return view('transit-area.invoice-customer-ar-list-report.index');
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
                stripos($i['warehouse'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('saldo_piutang_fmt', fn($r) => 'Rp ' . number_format((int)($r['saldo_piutang'] ?? 0), 0, ',', '.'))
            ->addColumn('saldo_piutang_end_fmt', fn($r) => 'Rp ' . number_format((int)($r['saldo_piutang_end'] ?? 0), 0, ',', '.'))
            ->addColumn('total_piutang_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_piutang'] ?? 0), 0, ',', '.'))
            ->addColumn('sales_fmt', fn($r) => 'Rp ' . number_format((int)($r['sales'] ?? 0), 0, ',', '.'))
            ->addColumn('jan_fmt', fn($r) => 'Rp ' . number_format((int)($r['jan'] ?? 0), 0, ',', '.'))
            ->addColumn('feb_fmt', fn($r) => 'Rp ' . number_format((int)($r['feb'] ?? 0), 0, ',', '.'))
            ->addColumn('mar_fmt', fn($r) => 'Rp ' . number_format((int)($r['mar'] ?? 0), 0, ',', '.'))
            ->addColumn('apr_fmt', fn($r) => 'Rp ' . number_format((int)($r['apr'] ?? 0), 0, ',', '.'))
            ->addColumn('mei_fmt', fn($r) => 'Rp ' . number_format((int)($r['mei'] ?? 0), 0, ',', '.'))
            ->addColumn('jun_fmt', fn($r) => 'Rp ' . number_format((int)($r['jun'] ?? 0), 0, ',', '.'))
            ->addColumn('jul_fmt', fn($r) => 'Rp ' . number_format((int)($r['jul'] ?? 0), 0, ',', '.'))
            ->addColumn('agt_fmt', fn($r) => 'Rp ' . number_format((int)($r['agt'] ?? 0), 0, ',', '.'))
            ->addColumn('sep_fmt', fn($r) => 'Rp ' . number_format((int)($r['sep'] ?? 0), 0, ',', '.'))
            ->addColumn('okt_fmt', fn($r) => 'Rp ' . number_format((int)($r['okt'] ?? 0), 0, ',', '.'))
            ->addColumn('nov_fmt', fn($r) => 'Rp ' . number_format((int)($r['nov'] ?? 0), 0, ',', '.'))
            ->addColumn('des_fmt', fn($r) => 'Rp ' . number_format((int)($r['des'] ?? 0), 0, ',', '.'))
            ->make(true);
    }

    public function show($id)
    {
        $d = $this->store->find($id);
        return $d ? response()->json(['success' => true, 'data' => $d]) : response()->json(['message' => 'Data tidak ditemukan.'], 404);
    }
}