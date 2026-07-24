<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ArWarehouseReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('ar-warehouse');
        View::share('activeMenu', 'ar-warehouse-report');
    }

    public function index()
    {
        return view('Sales-distribution.ar-warehouse-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['customer_name'] ?? '', $q) !== false ||
                stripos($i['warehouse_name'] ?? '', $q) !== false ||
                stripos($i['invoice_no'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_warehouse')) {
            $w = $request->filter_warehouse;
            if ($w !== 'all') $data = array_filter($data, fn($i) => ($i['warehouse_id'] ?? '') === $w);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('invoice_date_fmt', fn($r) => \Carbon\Carbon::parse($r['invoice_date'])->format('d/m/Y'))
            ->addColumn('due_date_fmt', fn($r) => \Carbon\Carbon::parse($r['due_date'])->format('d/m/Y'))
            ->addColumn('outstanding_fmt', fn($r) => 'Rp ' . number_format((int)($r['outstanding_amount'] ?? 0), 0, ',', '.'))
            ->addColumn('age_badge', function ($r) {
                $age = (int)($r['age_days'] ?? 0);
                $cls = $age > 30 ? 'bg-danger' : ($age > 14 ? 'bg-warning text-dark' : 'bg-success');
                return '<span class="badge ' . $cls . '">' . $age . ' hari</span>';
            })
            ->rawColumns(['age_badge'])->make(true);
    }
}
