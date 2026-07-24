<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class CustomerOutstandingPerDateReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('customer-outstanding-per-date-report');
        View::share('activeMenu', 'customer-outstanding-per-date-report');
    }

    public function index()
    {
        return view('Sales-distribution.customer-outstanding-per-date-report.index');
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

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('current_fmt', fn($r) => 'Rp ' . number_format((int)($r['current'] ?? 0), 0, ',', '.'))
            ->addColumn('days_1_30_fmt', fn($r) => 'Rp ' . number_format((int)($r['days_1_30'] ?? 0), 0, ',', '.'))
            ->addColumn('days_31_60_fmt', fn($r) => 'Rp ' . number_format((int)($r['days_31_60'] ?? 0), 0, ',', '.'))
            ->addColumn('days_61_90_fmt', fn($r) => 'Rp ' . number_format((int)($r['days_61_90'] ?? 0), 0, ',', '.'))
            ->addColumn('days_90_plus_fmt', fn($r) => 'Rp ' . number_format((int)($r['days_90_plus'] ?? 0), 0, ',', '.'))
            ->addColumn('total_outstanding_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_outstanding'] ?? 0), 0, ',', '.'))
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