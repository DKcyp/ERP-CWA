<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ProfitLossReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('profit-loss-reports');
        View::share('activeMenu', 'profit-loss-report');
    }

    public function index()
    {
        return view('Sales-distribution.profit-loss-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['period'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('revenue_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_sales_revenue'] ?? 0), 0, ',', '.'))
            ->addColumn('return_fmt', fn($r) => 'Rp ' . number_format((int)($r['sales_return'] ?? 0), 0, ',', '.'))
            ->addColumn('hpp_fmt', fn($r) => 'Rp ' . number_format((int)($r['cogs'] ?? 0), 0, ',', '.'))
            ->addColumn('gross_fmt', fn($r) => 'Rp ' . number_format((int)($r['gross_margin'] ?? 0), 0, ',', '.'))
            ->addColumn('expense_fmt', fn($r) => 'Rp ' . number_format((int)($r['operating_expenses'] ?? 0), 0, ',', '.'))
            ->addColumn('net_fmt', fn($r) => 'Rp ' . number_format((int)($r['net_sales_profit'] ?? 0), 0, ',', '.'))
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