<?php

namespace App\Http\Controllers\TransitArea;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class DailySalesByBrandReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('daily-sales-by-brand-reports');
        View::share('activeMenu', 'daily-sales-by-brand-report');
    }

    public function index()
    {
        return view('transit-area.daily-sales-by-brand-report.index');
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
                stripos($i['brand_id'] ?? '', $q) !== false ||
                stripos($i['brand_name'] ?? '', $q) !== false ||
                stripos($i['warehouse'] ?? '', $q) !== false ||
                stripos($i['area'] ?? '', $q) !== false
            );
        }

        $data = array_values($data);
        $totalNet = array_sum(array_map(fn($i) => (int)($i['net_sales_amount'] ?? 0), $data));

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('total_qty_fmt', fn($r) => number_format((float)($r['total_qty_sold'] ?? 0), 0, ',', '.'))
            ->addColumn('gross_fmt', fn($r) => 'Rp ' . number_format((int)($r['gross_amount'] ?? 0), 0, ',', '.'))
            ->addColumn('discount_fmt', fn($r) => 'Rp ' . number_format((int)($r['discount_amount'] ?? 0), 0, ',', '.'))
            ->addColumn('net_fmt', fn($r) => 'Rp ' . number_format((int)($r['net_sales_amount'] ?? 0), 0, ',', '.'))
            ->addColumn('pct_fmt', function ($r) use ($totalNet) {
                $net = (int)($r['net_sales_amount'] ?? 0);
                $pct = $totalNet > 0 ? round(($net / $totalNet) * 100, 2) : 0;
                return '<span class="badge bg-info text-dark">' . number_format($pct, 2, ',', '.') . '%</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-detail" data-id="' . $row['id'] . '"><i class="bi bi-eye"></i></button>
                </div>';
            })
            ->rawColumns(['pct_fmt','action'])->make(true);
    }

    public function show($id)
    {
        $d = $this->store->find($id);
        return $d ? response()->json(['success'=>true,'data'=>$d]) : response()->json(['message'=>'Data tidak ditemukan.'],404);
    }
}