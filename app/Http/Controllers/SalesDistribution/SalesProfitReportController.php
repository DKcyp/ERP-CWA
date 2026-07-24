<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SalesProfitReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('sales-profit-reports');
        View::share('activeMenu', 'sales-profit-report');
    }

    public function index()
    {
        return view('Sales-distribution.sales-profit-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['invoice_no'] ?? '', $q) !== false ||
                stripos($i['customer_name'] ?? '', $q) !== false ||
                stripos($i['product_id'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('selling_price_fmt', fn($r) => number_format((int)($r['selling_price'] ?? 0), 0, ',', '.'))
            ->addColumn('hpp_cost_fmt', fn($r) => number_format((int)($r['hpp_cost'] ?? 0), 0, ',', '.'))
            ->addColumn('gross_profit_fmt', fn($r) => number_format((int)($r['gross_profit'] ?? 0), 0, ',', '.'))
            ->addColumn('profit_margin_fmt', fn($r) => ((float)($r['profit_margin'] ?? 0)) . '%')
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