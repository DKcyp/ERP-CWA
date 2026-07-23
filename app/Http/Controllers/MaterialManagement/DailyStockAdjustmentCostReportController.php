<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class DailyStockAdjustmentCostReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('stock-adjustments');
        View::share('activeMenu', 'daily-stock-adjustment-cost-report');
    }

    public function index()
    {
        return view('material-management.daily-stock-adjustment-cost-report.index');
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
                stripos($i['department'] ?? '', $q) !== false
            );
        }

        // Flatten items + compute cost diff
        $rows = [];
        foreach (array_values($data) as $h) {
            $items = $h['items'] ?? [];
            if (empty($items)) {
                $rows[] = array_merge($h, [
                    'line_material'    => '-',
                    'line_sys_qty'     => 0,
                    'line_phys_qty'    => 0,
                    'line_qty_diff'    => 0,
                    'line_cost_unit'   => 0,
                    'line_cost_diff'   => 0,
                ]);
            } else {
                foreach ($items as $item) {
                    $sysQty   = (float)($item['system_qty'] ?? 0);
                    $physQty  = (float)($item['physical_qty'] ?? 0);
                    $costUnit = (int)($item['cost_per_unit'] ?? 0);
                    $diff     = $sysQty - $physQty;
                    $rows[] = array_merge($h, [
                        'line_material'  => $item['material'] ?? '-',
                        'line_sys_qty'   => $sysQty,
                        'line_phys_qty'  => $physQty,
                        'line_qty_diff'  => $diff,
                        'line_cost_unit' => $costUnit,
                        'line_cost_diff' => $diff * $costUnit,
                    ]);
                }
            }
        }

        return DataTables::of($rows)
            ->addIndexColumn()
            ->addColumn('adjustment_date_fmt', fn($row) => \Carbon\Carbon::parse($row['adjustment_date'])->format('d/m/Y'))
            ->addColumn('type_badge', function ($row) {
                $t = $row['adjustment_type'] ?? '';
                $cls = $t === 'INTERNAL_USE' ? 'bg-warning text-dark' : 'bg-primary';
                return '<span class="badge ' . $cls . '">' . $t . '</span>';
            })
            ->addColumn('line_cost_unit_fmt', function ($row) {
                return 'Rp ' . number_format((int)($row['line_cost_unit'] ?? 0), 0, ',', '.');
            })
            ->addColumn('line_cost_diff_fmt', function ($row) {
                $v = (int)($row['line_cost_diff'] ?? 0);
                $cls = $v < 0 ? 'text-danger' : ($v > 0 ? 'text-success' : '');
                return '<span class="' . $cls . '">Rp ' . number_format($v, 0, ',', '.') . '</span>';
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
            ->rawColumns(['type_badge', 'line_cost_diff_fmt', 'status_badge', 'action'])
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
        $totalCostDiff    = 0;
        $totalItems       = 0;
        $statusCounts     = [];

        foreach ($data as $h) {
            foreach ($h['items'] ?? [] as $item) {
                $sysQty  = (float)($item['system_qty'] ?? 0);
                $physQty = (float)($item['physical_qty'] ?? 0);
                $cost    = (int)($item['cost_per_unit'] ?? 0);
                $totalCostDiff += ($sysQty - $physQty) * $cost;
                $totalItems++;
            }
            $s = $h['status'] ?? 'DRAFT';
            $statusCounts[$s] = ($statusCounts[$s] ?? 0) + 1;
        }

        return response()->json([
            'success'           => true,
            'total_adjustments' => $totalAdjustments,
            'total_cost_diff'   => 'Rp ' . number_format($totalCostDiff, 0, ',', '.'),
            'total_cost_diff_raw' => $totalCostDiff,
            'total_items'       => $totalItems,
            'status_counts'     => $statusCounts,
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
