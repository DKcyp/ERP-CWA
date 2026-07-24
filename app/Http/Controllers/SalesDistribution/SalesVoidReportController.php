<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SalesVoidReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('sales-void-reports');
        View::share('activeMenu', 'sales-void-report');
    }

    public function index()
    {
        return view('Sales-distribution.sales-void-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['doc_no'] ?? '', $q) !== false ||
                stripos($i['customer_name'] ?? '', $q) !== false ||
                stripos($i['void_reason'] ?? '', $q) !== false ||
                stripos($i['authorized_user'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('void_date_fmt', fn($r) => $r['void_date'] ? \Carbon\Carbon::parse($r['void_date'])->format('d/m/Y') : '-')
            ->addColumn('amount_fmt', fn($r) => number_format((int)($r['original_amount'] ?? 0), 0, ',', '.'))
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