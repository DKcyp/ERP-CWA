<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class OutstandingPerCustomerReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('outstanding-per-customer-report');
        View::share('activeMenu', 'outstanding-per-customer-report');
    }

    public function index()
    {
        return view('Sales-distribution.outstanding-per-customer-report.index');
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
            ->addColumn('total_outstanding_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_outstanding'] ?? 0), 0, ',', '.'))
            ->addColumn('credit_limit_fmt', fn($r) => 'Rp ' . number_format((int)($r['credit_limit'] ?? 0), 0, ',', '.'))
            ->addColumn('exceeded_fmt', fn($r) => 'Rp ' . number_format((int)($r['exceeded_amount'] ?? 0), 0, ',', '.'))
            ->addColumn('exceeded_badge', function ($row) {
                $exc = (int)($row['exceeded_amount'] ?? 0);
                if ($exc > 0) return '<span class="badge bg-danger">Melebihi</span>';
                return '<span class="badge bg-success">Normal</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-detail" data-id="' . $row['id'] . '"><i class="bi bi-eye"></i></button>
                </div>';
            })
            ->rawColumns(['exceeded_badge','action'])->make(true);
    }

    public function show($id)
    {
        $d = $this->store->find($id);
        return $d ? response()->json(['success'=>true,'data'=>$d]) : response()->json(['message'=>'Data tidak ditemukan.'],404);
    }
}