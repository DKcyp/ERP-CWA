<?php

namespace App\Http\Controllers\TransitArea;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class DailyPaymentRecapReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('daily-payment-recap-reports');
        View::share('activeMenu', 'daily-payment-recap-report');
    }

    public function index()
    {
        return view('transit-area.daily-payment-recap-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('start_date')) {
            $s = $request->start_date;
            $data = array_filter($data, fn($i) => ($i['date'] ?? '') >= $s);
        }
        if ($request->filled('end_date')) {
            $e = $request->end_date;
            $data = array_filter($data, fn($i) => ($i['date'] ?? '') <= $e);
        }
        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['no_ttp'] ?? '', $q) !== false ||
                stripos($i['customer_id'] ?? '', $q) !== false ||
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['sales_invoice'] ?? '', $q) !== false ||
                stripos($i['payment_id'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('tgl_ttp_fmt', fn($r) => $r['tgl_ttp'] ? \Carbon\Carbon::parse($r['tgl_ttp'])->format('d/m/Y') : '-')
            ->addColumn('due_date_fmt', fn($r) => $r['due_date'] ? \Carbon\Carbon::parse($r['due_date'])->format('d/m/Y') : '-')
            ->addColumn('bank_fmt', fn($r) => 'Rp ' . number_format((int)($r['bank'] ?? 0), 0, ',', '.'))
            ->addColumn('cash_fmt', fn($r) => 'Rp ' . number_format((int)($r['cash'] ?? 0), 0, ',', '.'))
            ->addColumn('discount_fmt', fn($r) => 'Rp ' . number_format((int)($r['discount'] ?? 0), 0, ',', '.'))
            ->addColumn('lain_lain_fmt', fn($r) => 'Rp ' . number_format((int)($r['lain_lain'] ?? 0), 0, ',', '.'))
            ->addColumn('retur_fmt', fn($r) => 'Rp ' . number_format((int)($r['retur'] ?? 0), 0, ',', '.'))
            ->addColumn('total_bank_in_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_bank_in'] ?? 0), 0, ',', '.'))
            ->addColumn('outstanding_fmt', fn($r) => 'Rp ' . number_format((int)($r['outstanding'] ?? 0), 0, ',', '.'))
            ->addColumn('invoice_total_fmt', fn($r) => 'Rp ' . number_format((int)($r['invoice_total'] ?? 0), 0, ',', '.'))
            ->addColumn('diskon_promo_fmt', fn($r) => ($r['diskon_promo'] ?? 0) . '%')
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-detail" data-id="' . $row['id'] . '"><i class="bi bi-eye"></i></button>
                </div>';
            })
            ->rawColumns(['action'])->make(true);
    }

    public function show($id)
    {
        $d = $this->store->find($id);
        return $d ? response()->json(['success'=>true,'data'=>$d]) : response()->json(['message'=>'Data tidak ditemukan.'],404);
    }
}