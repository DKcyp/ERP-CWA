<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class StockAdjustmentListController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('stock-adjustments');
        View::share('activeMenu', 'stock-adjustment-list');
    }

    public function index()
    {
        return view('material-management.stock-adjustment-list.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        $type = $request->input('type', 'all');
        if ($type !== 'all') {
            $data = array_filter($data, fn($i) => ($i['adjustment_type'] ?? '') === $type);
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
                stripos($i['reason'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('adjustment_date_fmt', fn($row) => \Carbon\Carbon::parse($row['adjustment_date'])->format('d/m/Y'))
            ->addColumn('type_badge', function ($row) {
                $type = $row['adjustment_type'] ?? 'STANDARD';
                $class = $type === 'INTERNAL_USE' ? 'bg-warning text-dark' : 'bg-primary';
                return '<span class="badge ' . $class . '">' . $type . '</span>';
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
                $id = $row['id'];
                $status = $row['status'] ?? 'DRAFT';
                $btns = '<div class="btn-group btn-group-sm">';
                $btns .= '<button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $id . '"><i class="bi bi-pencil"></i></button>';
                if (in_array($status, ['DRAFT'])) {
                    $btns .= '<button type="button" class="btn btn-outline-success btn-approve" data-id="' . $id . '"><i class="bi bi-check-lg"></i></button>';
                }
                $btns .= '<button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $id . '"><i class="bi bi-trash"></i></button>';
                $btns .= '</div>';
                return $btns;
            })
            ->rawColumns(['type_badge', 'status_badge', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'adjustment_number' => ['required', 'string', 'max:50'],
            'adjustment_date'   => ['required', 'date'],
            'warehouse'         => ['nullable', 'string', 'max:100'],
            'department'        => ['nullable', 'string', 'max:100'],
            'adjustment_type'   => ['required', 'string', 'in:STANDARD,INTERNAL_USE'],
            'use_for'           => ['nullable', 'string', 'max:200'],
            'transfer_to_ta'    => ['nullable', 'string', 'max:50'],
            'product_group'     => ['nullable', 'string', 'max:100'],
            'pic'               => ['nullable', 'string', 'max:100'],
            'user_id'           => ['nullable', 'string', 'max:50'],
            'reason'            => ['nullable', 'string'],
            'status'            => ['required', 'string', 'in:DRAFT,APPROVED,COMPLETED'],
            'items'             => ['nullable', 'string'],
        ]);

        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $items = array_map(fn($i) => $i + [
            'system_qty'    => (int)($i['system_qty'] ?? 0),
            'physical_qty'  => (int)($i['physical_qty'] ?? 0),
            'cost_per_unit' => (int)($i['cost_per_unit'] ?? 0),
        ], $items);

        $this->store->create($request->only('adjustment_number', 'adjustment_date', 'warehouse', 'department', 'adjustment_type', 'use_for', 'transfer_to_ta', 'product_group', 'pic', 'user_id', 'reason', 'status') + [
            'items' => $items,
        ]);

        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    public function show($id)
    {
        return response()->json([
            'success' => true,
            'data'    => $this->store->find($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'adjustment_number' => ['required', 'string', 'max:50'],
            'adjustment_date'   => ['required', 'date'],
            'warehouse'         => ['nullable', 'string', 'max:100'],
            'department'        => ['nullable', 'string', 'max:100'],
            'adjustment_type'   => ['required', 'string', 'in:STANDARD,INTERNAL_USE'],
            'use_for'           => ['nullable', 'string', 'max:200'],
            'transfer_to_ta'    => ['nullable', 'string', 'max:50'],
            'product_group'     => ['nullable', 'string', 'max:100'],
            'pic'               => ['nullable', 'string', 'max:100'],
            'user_id'           => ['nullable', 'string', 'max:50'],
            'reason'            => ['nullable', 'string'],
            'status'            => ['required', 'string', 'in:DRAFT,APPROVED,COMPLETED'],
            'items'             => ['nullable', 'string'],
        ]);

        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $items = array_map(fn($i) => $i + [
            'system_qty'    => (int)($i['system_qty'] ?? 0),
            'physical_qty'  => (int)($i['physical_qty'] ?? 0),
            'cost_per_unit' => (int)($i['cost_per_unit'] ?? 0),
        ], $items);

        $this->store->update($id, $request->only('adjustment_number', 'adjustment_date', 'warehouse', 'department', 'adjustment_type', 'use_for', 'transfer_to_ta', 'product_group', 'pic', 'user_id', 'reason', 'status') + [
            'items' => $items,
        ]);

        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:APPROVED,COMPLETED'],
        ]);

        $this->store->update($id, [
            'status' => $request->input('status'),
        ]);

        return response()->json(['message' => 'Status berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}
