<?php

namespace App\Http\Controllers\TransitArea;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class DailySalesPoClosingReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('daily-sales-po-closing-reports');
        View::share('activeMenu', 'daily-sales-po-closing-report');
    }

    public function index()
    {
        return view('transit-area.daily-sales-po-closing-report.index');
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
                stripos($i['sales_invoice'] ?? '', $q) !== false ||
                stripos($i['customer_id'] ?? '', $q) !== false ||
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['product_id'] ?? '', $q) !== false ||
                stripos($i['delivery_order'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('due_date_fmt', fn($r) => $r['due_date'] ? \Carbon\Carbon::parse($r['due_date'])->format('d/m/Y') : '-')
            ->addColumn('qty_fmt', fn($r) => number_format((float)($r['qty'] ?? 0), 2, ',', '.'))
            ->addColumn('price_fmt', fn($r) => 'Rp ' . number_format((int)($r['price'] ?? 0), 0, ',', '.'))
            ->addColumn('disc_pct_fmt', fn($r) => ($r['disc_pct'] ?? 0) . '%')
            ->addColumn('disc_amt_fmt', fn($r) => 'Rp ' . number_format((int)($r['disc_amount'] ?? 0), 0, ',', '.'))
            ->addColumn('total_potongan_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_potongan'] ?? 0), 0, ',', '.'))
            ->addColumn('total_fmt', fn($r) => 'Rp ' . number_format((int)($r['total'] ?? 0), 0, ',', '.'))
            ->addColumn('dpp_fmt', fn($r) => 'Rp ' . number_format((int)($r['dpp'] ?? 0), 0, ',', '.'))
            ->addColumn('ppn_fmt', fn($r) => 'Rp ' . number_format((int)($r['ppn'] ?? 0), 0, ',', '.'))
            ->addColumn('grand_total_fmt', fn($r) => 'Rp ' . number_format((int)($r['grand_total'] ?? 0), 0, ',', '.'))
            ->addColumn('tonase_fmt', fn($r) => number_format((float)($r['tonase'] ?? 0), 2, ',', '.') . ' kg')
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