<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SalesCommisionReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('sales-commision-reports');
        View::share('activeMenu', 'sales-commision-report');
    }

    public function index()
    {
        return view('Sales-distribution.sales-commision-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['salesman_id'] ?? '', $q) !== false ||
                stripos($i['salesman_name'] ?? '', $q) !== false ||
                stripos($i['period'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('total_omset_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_omset'] ?? 0), 0, ',', '.'))
            ->addColumn('target_fmt', fn($r) => 'Rp ' . number_format((int)($r['target'] ?? 0), 0, ',', '.'))
            ->addColumn('commission_rate_fmt', fn($r) => ($r['commission_rate'] ?? 0) . '%')
            ->addColumn('total_commission_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_commission'] ?? 0), 0, ',', '.'))
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