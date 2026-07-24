<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ClaimProductDailyReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('claim-product-daily-reports');
        View::share('activeMenu', 'claim-product-daily-report');
    }

    public function index()
    {
        return view('Sales-distribution.claim-product-daily-report.index');
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
                stripos($i['claim_doc_no'] ?? '', $q) !== false ||
                stripos($i['customer_id'] ?? '', $q) !== false ||
                stripos($i['customer_name'] ?? '', $q) !== false ||
                stripos($i['product_id'] ?? '', $q) !== false ||
                stripos($i['user'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($row) => \Carbon\Carbon::parse($row['date'])->format('d/m/Y'))
            ->addColumn('qty_fmt', fn($row) => number_format((int)($row['qty_claimed'] ?? 0), 0, ',', '.'))
            ->addColumn('points_fmt', fn($row) => number_format((int)($row['total_points_deducted'] ?? 0), 0, ',', '.'))
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-detail" data-id="' . $row['id'] . '"><i class="bi bi-eye"></i></button>
                </div>';
            })
            ->rawColumns(['action'])->make(true);
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

        $totalClaims   = count($data);
        $totalQty      = array_sum(array_map(fn($i) => (int)($i['qty_claimed'] ?? 0), $data));
        $totalPoints   = array_sum(array_map(fn($i) => (int)($i['total_points_deducted'] ?? 0), $data));

        return response()->json([
            'success'      => true,
            'total_claims' => $totalClaims,
            'total_qty'    => $totalQty,
            'total_points' => $totalPoints,
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
