<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class DailyStockAdjustmentReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('stock-adjustments');
        View::share('activeMenu', 'daily-stock-adjustment-report');
    }

    public function index()
    {
        return view('material-management.daily-stock-adjustment-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        if ($startDate || $endDate) {
            $data = array_filter($data, function ($item) use ($startDate, $endDate) {
                $date = $item['adjustment_date'] ?? '';
                if ($startDate && $date < $startDate) return false;
                if ($endDate && $date > $endDate) return false;
                return true;
            });
        }

        if ($request->filled('filter_type')) {
            $type = $request->filter_type;
            if ($type !== 'all') {
                $data = array_filter($data, fn($i) => ($i['adjustment_type'] ?? '') === $type);
            }
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
                stripos($i['adjustment_number'] ?? '', $q) !== false ||
                stripos($i['warehouse'] ?? '', $q) !== false ||
                stripos($i['department'] ?? '', $q) !== false ||
                stripos($i['pic'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('adjustment_date_fmt', fn($row) => \Carbon\Carbon::parse($row['adjustment_date'])->format('d/m/Y'))
            ->addColumn('total_items', fn($row) => count($row['items'] ?? []))
            ->addColumn('total_qty_diff', function ($row) {
                $total = 0;
                foreach ($row['items'] ?? [] as $item) {
                    $sys  = (float)($item['system_qty'] ?? 0);
                    $phys = (float)($item['physical_qty'] ?? 0);
                    $total += $sys - $phys;
                }
                return $total;
            })
            ->addColumn('type_badge', function ($row) {
                $t = $row['adjustment_type'] ?? '';
                $cls = $t === 'INTERNAL_USE' ? 'bg-warning text-dark' : 'bg-primary';
                return '<span class="badge ' . $cls . '">' . $t . '</span>';
            })
            ->addColumn('status_badge', function ($row) {
                $map = [
                    'DRAFT'     => ['class' => 'bg-secondary',          'label' => 'Draft'],
                    'APPROVED'  => ['class' => 'bg-info text-dark',     'label' => 'Approved'],
                    'COMPLETED' => ['class' => 'bg-success',            'label' => 'Completed'],
                ];
                $s = $row['status'] ?? 'DRAFT';
                $c = $map[$s]['class'] ?? 'bg-secondary';
                $l = $map[$s]['label'] ?? $s;
                return '<span class="badge ' . $c . '">' . $l . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-detail" data-id="' . $row['id'] . '"><i class="bi bi-eye"></i></button>
                </div>';
            })
            ->rawColumns(['type_badge', 'status_badge', 'action'])
            ->make(true);
    }

    public function summary(Request $request)
    {
        $data = $this->store->all();

        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        if ($startDate || $endDate) {
            $data = array_filter($data, function ($item) use ($startDate, $endDate) {
                $date = $item['adjustment_date'] ?? '';
                if ($startDate && $date < $startDate) return false;
                if ($endDate && $date > $endDate) return false;
                return true;
            });
        }

        $totalAdjustments = count($data);
        $totalItems       = 0;
        $totalQtyDiff     = 0;
        $statusCounts     = [];

        foreach ($data as $h) {
            foreach ($h['items'] ?? [] as $item) {
                $sysQty  = (float)($item['system_qty'] ?? 0);
                $physQty = (float)($item['physical_qty'] ?? 0);
                $totalQtyDiff += ($sysQty - $physQty);
                $totalItems++;
            }
            $s = $h['status'] ?? 'DRAFT';
            $statusCounts[$s] = ($statusCounts[$s] ?? 0) + 1;
        }

        return response()->json([
            'success'              => true,
            'total_adjustments'    => $totalAdjustments,
            'total_items'          => $totalItems,
            'total_qty_diff'       => $totalQtyDiff,
            'status_counts'        => $statusCounts,
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
