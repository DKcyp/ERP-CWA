<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class DailyStockTransferReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('stock-transfers');
        View::share('activeMenu', 'daily-stock-transfer-report');
    }

    public function index()
    {
        return view('material-management.daily-stock-transfer-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        if ($startDate || $endDate) {
            $data = array_filter($data, function ($item) use ($startDate, $endDate) {
                $date = $item['transfer_date'] ?? '';
                if ($startDate && $date < $startDate) return false;
                if ($endDate && $date > $endDate) return false;
                return true;
            });
        }

        if ($request->filled('filter_status')) {
            $status = $request->filter_status;
            if ($status !== 'all') {
                $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $status);
            }
        }

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['transfer_number'] ?? '', $q) !== false ||
                stripos($i['from_warehouse'] ?? '', $q) !== false ||
                stripos($i['to_warehouse'] ?? '', $q) !== false ||
                stripos($i['pic'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('transfer_date_fmt', fn($row) => \Carbon\Carbon::parse($row['transfer_date'])->format('d/m/Y'))
            ->addColumn('total_items', fn($row) => count($row['items'] ?? []))
            ->addColumn('total_qty', function ($row) {
                return array_sum(array_map(fn($i) => (int)($i['qty'] ?? 0), $row['items'] ?? []));
            })
            ->addColumn('status_badge', function ($row) {
                $map = [
                    'PREPARATION' => ['class' => 'bg-secondary',          'label' => 'Preparation'],
                    'SHIPMENT'    => ['class' => 'bg-info text-dark',     'label' => 'Shipment'],
                    'TRANSFER'    => ['class' => 'bg-success',            'label' => 'Transfer'],
                ];
                $s = $row['status'] ?? 'PREPARATION';
                $c = $map[$s]['class'] ?? 'bg-secondary';
                $l = $map[$s]['label'] ?? $s;
                return '<span class="badge ' . $c . '">' . $l . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-detail" data-id="' . $row['id'] . '"><i class="bi bi-eye"></i></button>
                </div>';
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    public function summary(Request $request)
    {
        $data = $this->store->all();

        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        if ($startDate || $endDate) {
            $data = array_filter($data, function ($item) use ($startDate, $endDate) {
                $date = $item['transfer_date'] ?? '';
                if ($startDate && $date < $startDate) return false;
                if ($endDate && $date > $endDate) return false;
                return true;
            });
        }

        $totalTransfers = count($data);
        $totalQty       = 0;
        $totalItems     = 0;
        $statusCounts   = [];

        foreach ($data as $h) {
            foreach ($h['items'] ?? [] as $item) {
                $totalQty += (int)($item['qty'] ?? 0);
                $totalItems++;
            }
            $s = $h['status'] ?? 'PREPARATION';
            $statusCounts[$s] = ($statusCounts[$s] ?? 0) + 1;
        }

        return response()->json([
            'success'          => true,
            'total_transfers'  => $totalTransfers,
            'total_qty'        => $totalQty,
            'total_items'      => $totalItems,
            'status_counts'    => $statusCounts,
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
