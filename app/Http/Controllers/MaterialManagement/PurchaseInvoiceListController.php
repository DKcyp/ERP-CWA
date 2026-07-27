<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class PurchaseInvoiceListController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('purchase-invoices');
        View::share('activeMenu', 'purchase-invoice-list');
    }

    public function index()
    {
        return view('material-management.purchase-invoice.index');
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
                stripos($i['invoice_number'] ?? '', $q) !== false ||
                stripos($i['supplier_name'] ?? '', $q) !== false ||
                stripos($i['stbj_number'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('invoice_date_fmt', fn($row) => \Carbon\Carbon::parse($row['invoice_date'])->format('d/m/Y'))
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
                    'PAID'      => ['class' => 'bg-success',            'label' => 'Paid'],
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
            'invoice_number' => ['required', 'string', 'max:50'],
            'invoice_date'   => ['required', 'date'],
            'due_date'       => ['nullable', 'date'],
            'stbj_number'      => ['required', 'string', 'max:50'],
            'supplier_name'  => ['required', 'string', 'max:200'],
            'currency'       => ['nullable', 'string', 'max:10'],
            'rate'           => ['nullable', 'integer', 'min:1'],
            'total'          => ['nullable', 'integer', 'min:0'],
            'paid_amount'    => ['nullable', 'integer', 'min:0'],
            'term'           => ['nullable', 'string', 'max:50'],
            'note'           => ['nullable', 'string'],
            'status'         => ['required', 'string', 'in:DRAFT,PENDING,APPROVED,REJECTED,PAID'],
            'items'          => ['nullable', 'string'],
        ]);

        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $items = array_map(fn($i) => $i + ['price' => (int)($i['price'] ?? 0)], $items);

        $this->store->create($request->only('invoice_number', 'invoice_date', 'due_date', 'stbj_number', 'supplier_name', 'currency', 'rate', 'total', 'paid_amount', 'term', 'note', 'status') + [
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
            'invoice_number' => ['required', 'string', 'max:50'],
            'invoice_date'   => ['required', 'date'],
            'due_date'       => ['nullable', 'date'],
            'stbj_number'      => ['required', 'string', 'max:50'],
            'supplier_name'  => ['required', 'string', 'max:200'],
            'currency'       => ['nullable', 'string', 'max:10'],
            'rate'           => ['nullable', 'integer', 'min:1'],
            'total'          => ['nullable', 'integer', 'min:0'],
            'paid_amount'    => ['nullable', 'integer', 'min:0'],
            'term'           => ['nullable', 'string', 'max:50'],
            'note'           => ['nullable', 'string'],
            'status'         => ['required', 'string', 'in:DRAFT,PENDING,APPROVED,REJECTED,PAID'],
            'items'          => ['nullable', 'string'],
        ]);

        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $items = array_map(fn($i) => $i + ['price' => (int)($i['price'] ?? 0)], $items);

        $this->store->update($id, $request->only('invoice_number', 'invoice_date', 'due_date', 'stbj_number', 'supplier_name', 'currency', 'rate', 'total', 'paid_amount', 'term', 'note', 'status') + [
            'items' => $items,
        ]);

        return response()->json(['message' => 'Data berhasil diperbarui.']);
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

    public function destroy($id)
    {
        $this->store->delete($id);

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}
