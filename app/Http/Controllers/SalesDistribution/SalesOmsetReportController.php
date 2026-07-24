<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SalesOmsetReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('sales-omset-reports');
        View::share('activeMenu', 'sales-omset-report');
    }

    public function index()
    {
        return view('Sales-distribution.sales-omset-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['salesman'] ?? '', $q) !== false ||
                stripos($i['area'] ?? '', $q) !== false ||
                stripos($i['customer_group'] ?? '', $q) !== false ||
                stripos($i['period'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('gross_fmt', fn($r) => number_format((int)($r['total_gross_sales'] ?? 0), 0, ',', '.'))
            ->addColumn('discount_fmt', fn($r) => number_format((int)($r['total_discount'] ?? 0), 0, ',', '.'))
            ->addColumn('net_fmt', fn($r) => number_format((int)($r['total_net_omset'] ?? 0), 0, ',', '.'))
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