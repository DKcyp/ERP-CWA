<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class StockTransferListController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('stock-transfers');
        View::share('activeMenu', 'stock-transfer-list');
    }

    public function index()
    {
        return view('material-management.stock-transfer-list.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

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
                $total = array_sum(array_map(fn($i) => (int)($i['qty'] ?? 0), $row['items'] ?? []));
                return $total;
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
                $id = $row['id'];
                $status = $row['status'] ?? 'PREPARATION';
                $btns = '<div class="btn-group btn-group-sm">';
                $btns .= '<button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $id . '"><i class="bi bi-pencil"></i></button>';
                if ($status === 'PREPARATION') {
                    $btns .= '<button type="button" class="btn btn-outline-warning btn-approve" data-status="SHIPMENT" data-id="' . $id . '"><i class="bi bi-truck"></i></button>';
                } elseif ($status === 'SHIPMENT') {
                    $btns .= '<button type="button" class="btn btn-outline-success btn-approve" data-status="TRANSFER" data-id="' . $id . '"><i class="bi bi-check-lg"></i></button>';
                }
                $btns .= '<button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $id . '"><i class="bi bi-trash"></i></button>';
                $btns .= '</div>';
                return $btns;
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'transfer_number'  => ['required', 'string', 'max:50'],
            'transfer_date'    => ['required', 'date'],
            'from_warehouse'   => ['required', 'string', 'max:100'],
            'to_warehouse'     => ['required', 'string', 'max:100'],
            'pic'              => ['nullable', 'string', 'max:100'],
            'reason'           => ['nullable', 'string'],
            'status'           => ['required', 'string', 'in:PREPARATION,SHIPMENT,TRANSFER'],
            'user_id'          => ['nullable', 'string', 'max:50'],
            'items'            => ['nullable', 'string'],
        ]);

        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $items = array_map(fn($i) => $i + [
            'qty'   => (int)($i['qty'] ?? 0),
            'unit'  => $i['unit'] ?? '',
            'notes' => $i['notes'] ?? '',
        ], $items);

        $this->store->create($request->only('transfer_number', 'transfer_date', 'from_warehouse', 'to_warehouse', 'pic', 'reason', 'status', 'user_id') + [
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
            'transfer_number'  => ['required', 'string', 'max:50'],
            'transfer_date'    => ['required', 'date'],
            'from_warehouse'   => ['required', 'string', 'max:100'],
            'to_warehouse'     => ['required', 'string', 'max:100'],
            'pic'              => ['nullable', 'string', 'max:100'],
            'reason'           => ['nullable', 'string'],
            'status'           => ['required', 'string', 'in:PREPARATION,SHIPMENT,TRANSFER'],
            'user_id'          => ['nullable', 'string', 'max:50'],
            'items'            => ['nullable', 'string'],
        ]);

        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $items = array_map(fn($i) => $i + [
            'qty'   => (int)($i['qty'] ?? 0),
            'unit'  => $i['unit'] ?? '',
            'notes' => $i['notes'] ?? '',
        ], $items);

        $this->store->update($id, $request->only('transfer_number', 'transfer_date', 'from_warehouse', 'to_warehouse', 'pic', 'reason', 'status', 'user_id') + [
            'items' => $items,
        ]);

        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:SHIPMENT,TRANSFER'],
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
