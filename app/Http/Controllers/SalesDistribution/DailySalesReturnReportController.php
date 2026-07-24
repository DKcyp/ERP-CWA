<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class DailySalesReturnReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('daily-sales-return-report');
        View::share('activeMenu', 'daily-sales-return-report');
    }

    public function index()
    {
        return view('Sales-distribution.daily-sales-return-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_date_start')) {
            $s = $request->filter_date_start;
            $data = array_filter($data, fn($i) => ($i['date'] ?? '') >= $s);
        }
        if ($request->filled('filter_date_end')) {
            $e = $request->filter_date_end;
            $data = array_filter($data, fn($i) => ($i['date'] ?? '') <= $e);
        }
        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['return_no'] ?? '', $q) !== false ||
                stripos($i['customer_name'] ?? '', $q) !== false ||
                stripos($i['product_id'] ?? '', $q) !== false ||
                stripos($i['reason'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('total_amount_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_amount'] ?? 0), 0, ',', '.'))
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