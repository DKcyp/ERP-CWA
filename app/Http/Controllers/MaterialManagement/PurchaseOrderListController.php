<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class PurchaseOrderListController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('purchase-orders');
        View::share('activeMenu', 'purchase-order-list');
    }

    public function index()
    {
        return view('material-management.purchase-order.index');
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
                stripos($i['po_number'] ?? '', $q) !== false ||
                stripos($i['supplier_name'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('po_date_fmt', fn($row) => \Carbon\Carbon::parse($row['po_date'])->format('d/m/Y'))
            ->addColumn('total_items', fn($row) => count($row['items'] ?? []))
            ->addColumn('total_amount', function ($row) {
                $items = $row['items'] ?? [];
                $total = array_sum(array_map(fn($i) => ($i['qty'] ?? 0) * ($i['price'] ?? 0), $items));
                return 'Rp ' . number_format($total, 0, ',', '.');
            })
            ->addColumn('status_badge', function ($row) {
                $map = [
                    'DRAFT'     => ['class' => 'bg-secondary',          'label' => 'Draft'],
                    'PENDING'   => ['class' => 'bg-warning text-dark',  'label' => 'Pending'],
                    'APPROVED'  => ['class' => 'bg-info text-dark',     'label' => 'Approved'],
                    'REJECTED'  => ['class' => 'bg-danger',             'label' => 'Rejected'],
                    'FULFILLED' => ['class' => 'bg-success',            'label' => 'Fulfilled'],
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
                $btns .= '<button type="button" class="btn btn-outline-primary btn-detail" data-id="' . $id . '"><i class="bi bi-eye"></i></button>';
                $btns .= '<button type="button" class="btn btn-outline-warning btn-edit" data-id="' . $id . '"><i class="bi bi-pencil"></i></button>';
                if (in_array($status, ['DRAFT', 'PENDING'])) {
                    $btns .= '<button type="button" class="btn btn-outline-success btn-approve" data-id="' . $id . '"><i class="bi bi-check-lg"></i></button>';
                    $btns .= '<button type="button" class="btn btn-outline-danger btn-reject" data-id="' . $id . '"><i class="bi bi-x-lg"></i></button>';
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
            'po_number'     => ['required','string','max:50'],
            'po_date'       => ['required','date'],
            'supplier_name' => ['nullable','string','max:200'],
            'supplier_code' => ['nullable','string','max:50'],
            'note'          => ['nullable','string','max:500'],
            'status'        => ['required','string','in:DRAFT,PENDING,APPROVED,REJECTED,FULFILLED'],
            'items'         => ['nullable','string'],
        ]);

        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];

        $this->store->create(array_merge(
            $request->only('po_number','po_date','supplier_name','supplier_code','note','status'),
            ['items' => $items]
        ));
        return response()->json(['message' => 'PO berhasil ditambahkan.']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'po_number'     => ['required','string','max:50'],
            'po_date'       => ['required','date'],
            'supplier_name' => ['nullable','string','max:200'],
            'supplier_code' => ['nullable','string','max:50'],
            'note'          => ['nullable','string','max:500'],
            'status'        => ['required','string','in:DRAFT,PENDING,APPROVED,REJECTED,FULFILLED'],
            'items'         => ['nullable','string'],
        ]);

        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];

        $this->store->update($id, array_merge(
            $request->only('po_number','po_date','supplier_name','supplier_code','note','status'),
            ['items' => $items]
        ));
        return response()->json(['message' => 'PO berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'PO berhasil dihapus.']);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:APPROVED,REJECTED'],
        ]);

        $this->store->update($id, [
            'status' => $request->input('status'),
        ]);

        return response()->json(['message' => 'Status berhasil diperbarui.']);
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
