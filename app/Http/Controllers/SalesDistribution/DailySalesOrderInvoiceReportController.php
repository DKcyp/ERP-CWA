<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class DailySalesOrderInvoiceReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('daily-sales-order-invoice-reports');
        View::share('activeMenu', 'daily-sales-order-invoice-report');
    }

    public function index()
    {
        return view('Sales-distribution.daily-sales-order-invoice-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        if ($startDate || $endDate) {
            $data = array_filter($data, function ($item) use ($startDate, $endDate) {
                $date = $item['date'] ?? '';
                if ($startDate && $date < $startDate) return false;
                if ($endDate && $date > $endDate) return false;
                return true;
            });
        }

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['so_no'] ?? '', $q) !== false ||
                stripos($i['si_no'] ?? '', $q) !== false ||
                stripos($i['customer_name'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('so_amt_fmt', fn($r) => number_format((int)($r['so_amount'] ?? 0), 0, ',', '.'))
            ->addColumn('inv_amt_fmt', fn($r) => number_format((int)($r['invoiced_amount'] ?? 0), 0, ',', '.'))
            ->addColumn('fulfilment_pct', function ($r) {
                $soAmt = (int)($r['so_amount'] ?? 0);
                $invAmt = (int)($r['invoiced_amount'] ?? 0);
                $pct = $soAmt > 0 ? round(($invAmt / $soAmt) * 100, 1) : 0;
                $cls = $pct >= 100 ? 'bg-success' : ($pct >= 50 ? 'bg-warning text-dark' : 'bg-danger');
                return '<span class="badge ' . $cls . '">' . $pct . '%</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-detail" data-id="' . $row['id'] . '"><i class="bi bi-eye"></i></button>
                </div>';
            })
            ->rawColumns(['fulfilment_pct', 'action'])->make(true);
    }

    public function summary(Request $request)
    {
        $data = $this->store->all();

        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        if ($startDate || $endDate) {
            $data = array_filter($data, function ($item) use ($startDate, $endDate) {
                $date = $item['date'] ?? '';
                if ($startDate && $date < $startDate) return false;
                if ($endDate && $date > $endDate) return false;
                return true;
            });
        }

        $totalRecords = count($data);
        $totalSoAmt   = array_sum(array_map(fn($i) => (int)($i['so_amount'] ?? 0), $data));
        $totalInvAmt  = array_sum(array_map(fn($i) => (int)($i['invoiced_amount'] ?? 0), $data));
        $avgFulfilment = $totalSoAmt > 0 ? round(($totalInvAmt / $totalSoAmt) * 100, 1) : 0;

        return response()->json([
            'success'         => true,
            'total_records'   => $totalRecords,
            'total_so_amount' => $totalSoAmt,
            'total_inv_amount'=> $totalInvAmt,
            'avg_fulfilment'  => $avgFulfilment,
        ]);
    }

    public function show($id)
    {
        $item = $this->store->find($id);
        if (!$item) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }
        return response()->json(['success' => true, 'data' => $item]);
    }
}
