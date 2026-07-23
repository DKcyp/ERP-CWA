<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class PurchaseReturnListController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('purchase-returns');
        View::share('activeMenu', 'purchase-return-list');
    }

    public function index()
    {
        return view('material-management.purchase-return.index');
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
                stripos($i['return_number'] ?? '', $q) !== false ||
                stripos($i['supplier_name'] ?? '', $q) !== false ||
                stripos($i['note'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('return_date_fmt', fn($row) => \Carbon\Carbon::parse($row['return_date'])->format('d/m/Y'))
            ->addColumn('total_return_amount_fmt', function ($row) {
                $total = (int)($row['total_return_amount'] ?? 0);
                return 'Rp ' . number_format($total, 0, ',', '.');
            })
            ->addColumn('discount_amount_fmt', function ($row) {
                $val = (int)($row['discount_amount'] ?? 0);
                return 'Rp ' . number_format($val, 0, ',', '.');
            })
            ->addColumn('discount_percent_fmt', fn($row) => ($row['discount_percent'] ?? 0) . '%')
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
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'return_number'     => ['required', 'string', 'max:50'],
            'return_date'       => ['required', 'date'],
            'warehouse'         => ['nullable', 'string', 'max:100'],
            'supplier_id'       => ['nullable', 'string', 'max:50'],
            'supplier_name'     => ['required', 'string', 'max:200'],
            'currency'          => ['nullable', 'string', 'max:10'],
            'term'              => ['nullable', 'string', 'max:50'],
            'discount_percent'  => ['nullable', 'integer', 'min:0', 'max:100'],
            'discount_amount'   => ['nullable', 'integer', 'min:0'],
            'user_name'         => ['nullable', 'string', 'max:150'],
            'account'           => ['nullable', 'string', 'max:100'],
            'price_list'        => ['nullable', 'string', 'max:50'],
            'note'              => ['nullable', 'string'],
            'status'            => ['required', 'string', 'in:DRAFT,APPROVED,COMPLETED'],
            'items'             => ['nullable', 'string'],
        ]);

        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $items = array_map(fn($i) => $i + ['qty' => (int)($i['qty'] ?? 0), 'price' => (int)($i['price'] ?? 0)], $items);
        $subtotal = array_sum(array_map(fn($i) => ($i['qty'] ?? 0) * ($i['price'] ?? 0), $items));
        $discountAmount = (int)($request->input('discount_amount') ?? 0);
        $total = $subtotal - $discountAmount;

        $this->store->create($request->only('return_number', 'return_date', 'warehouse', 'supplier_id', 'supplier_name', 'currency', 'term', 'discount_percent', 'discount_amount', 'user_name', 'account', 'price_list', 'note', 'status') + [
            'total_return_amount' => $total,
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
            'return_number'     => ['required', 'string', 'max:50'],
            'return_date'       => ['required', 'date'],
            'warehouse'         => ['nullable', 'string', 'max:100'],
            'supplier_id'       => ['nullable', 'string', 'max:50'],
            'supplier_name'     => ['required', 'string', 'max:200'],
            'currency'          => ['nullable', 'string', 'max:10'],
            'term'              => ['nullable', 'string', 'max:50'],
            'discount_percent'  => ['nullable', 'integer', 'min:0', 'max:100'],
            'discount_amount'   => ['nullable', 'integer', 'min:0'],
            'user_name'         => ['nullable', 'string', 'max:150'],
            'account'           => ['nullable', 'string', 'max:100'],
            'price_list'        => ['nullable', 'string', 'max:50'],
            'note'              => ['nullable', 'string'],
            'status'            => ['required', 'string', 'in:DRAFT,APPROVED,COMPLETED'],
            'items'             => ['nullable', 'string'],
        ]);

        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $items = array_map(fn($i) => $i + ['qty' => (int)($i['qty'] ?? 0), 'price' => (int)($i['price'] ?? 0)], $items);
        $subtotal = array_sum(array_map(fn($i) => ($i['qty'] ?? 0) * ($i['price'] ?? 0), $items));
        $discountAmount = (int)($request->input('discount_amount') ?? 0);
        $total = $subtotal - $discountAmount;

        $this->store->update($id, $request->only('return_number', 'return_date', 'warehouse', 'supplier_id', 'supplier_name', 'currency', 'term', 'discount_percent', 'discount_amount', 'user_name', 'account', 'price_list', 'note', 'status') + [
            'total_return_amount' => $total,
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
