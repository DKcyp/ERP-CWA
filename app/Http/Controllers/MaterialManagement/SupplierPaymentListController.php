<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SupplierPaymentListController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('supplier-payments');
        View::share('activeMenu', 'supplier-payment-list');
    }

    public function index()
    {
        return view('material-management.supplier-payment.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        $paymentType = $request->input('payment_type', 'all');
        if ($paymentType !== 'all') {
            $data = array_filter($data, fn($i) => ($i['payment_type'] ?? 'Regular') === $paymentType);
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
                stripos($i['payment_number'] ?? '', $q) !== false ||
                stripos($i['supplier_id'] ?? '', $q) !== false ||
                stripos($i['supplier_name'] ?? '', $q) !== false ||
                stripos($i['invoice_number'] ?? '', $q) !== false ||
                stripos($i['note'] ?? '', $q) !== false ||
                stripos($i['user_name'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('payment_date_fmt', fn($row) => \Carbon\Carbon::parse($row['payment_date'])->format('d/m/Y'))
            ->addColumn('supplier_id', fn($row) => $row['supplier_id'] ?? '-')
            ->addColumn('total_items', fn($row) => count($row['items'] ?? []))
            ->addColumn('currency', fn($row) => $row['currency'] ?? 'IDR')
            ->addColumn('payment_type', function ($row) {
                $type = $row['payment_type'] ?? 'Regular';
                $class = $type === 'Down' ? 'bg-warning text-dark' : 'bg-primary';
                return '<span class="badge ' . $class . '">' . $type . '</span>';
            })
            ->addColumn('total_amount', function ($row) {
                $items = $row['items'] ?? [];
                $total = array_sum(array_map(fn($i) => (int)($i['amount'] ?? 0), $items));
                return 'Rp ' . number_format($total, 0, ',', '.');
            })
            ->addColumn('account', fn($row) => $row['account'] ?? '-')
            ->addColumn('user_name', fn($row) => $row['user_name'] ?? '-')
            ->addColumn('complete_date_fmt', function ($row) {
                return !empty($row['complete_date'])
                    ? \Carbon\Carbon::parse($row['complete_date'])->format('d/m/Y')
                    : '-';
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
            ->rawColumns(['status_badge', 'payment_type', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_number' => ['required', 'string', 'max:50'],
            'payment_date'   => ['required', 'date'],
            'supplier_id'    => ['nullable', 'string', 'max:50'],
            'supplier_name'  => ['required', 'string', 'max:200'],
            'currency'       => ['nullable', 'string', 'max:10'],
            'account'        => ['nullable', 'string', 'max:100'],
            'user_name'      => ['nullable', 'string', 'max:150'],
            'complete_date'  => ['nullable', 'date'],
            'stbj_number'    => ['nullable', 'string', 'max:50'],
            'invoice_number' => ['nullable', 'string', 'max:50'],
            'note'           => ['nullable', 'string'],
            'payment_type'   => ['required', 'string', 'in:Regular,Down'],
            'status'         => ['required', 'string', 'in:DRAFT,PENDING,APPROVED,REJECTED,PAID'],
        ]);

        $this->store->create($request->only('payment_number', 'payment_date', 'supplier_id', 'supplier_name', 'currency', 'payment_type', 'account', 'user_name', 'complete_date', 'stbj_number', 'invoice_number', 'note', 'status'));

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
            'payment_number' => ['required', 'string', 'max:50'],
            'payment_date'   => ['required', 'date'],
            'supplier_id'    => ['nullable', 'string', 'max:50'],
            'supplier_name'  => ['required', 'string', 'max:200'],
            'currency'       => ['nullable', 'string', 'max:10'],
            'account'        => ['nullable', 'string', 'max:100'],
            'user_name'      => ['nullable', 'string', 'max:150'],
            'complete_date'  => ['nullable', 'date'],
            'stbj_number'    => ['nullable', 'string', 'max:50'],
            'invoice_number' => ['nullable', 'string', 'max:50'],
            'note'           => ['nullable', 'string'],
            'payment_type'   => ['required', 'string', 'in:Regular,Down'],
            'status'         => ['required', 'string', 'in:DRAFT,PENDING,APPROVED,REJECTED,PAID'],
        ]);

        $this->store->update($id, $request->only('payment_number', 'payment_date', 'supplier_id', 'supplier_name', 'currency', 'payment_type', 'account', 'user_name', 'complete_date', 'stbj_number', 'invoice_number', 'note', 'status'));

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
