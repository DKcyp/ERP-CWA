<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PurchaseRequestController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('purchase-requests');
        $this->initDummyData();
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $data = [
            [
                'pr_number'    => 'PR-2026-0001',
                'pr_date'      => '2026-07-01',
                'requester'    => 'Budi Santoso',
                'department'   => 'Produksi',
                'total_items'  => 3,
                'total_qty'    => 150,
                'status'       => 'APPROVED',
                'note'         => 'Pengadaan bahan baku produksi bulan Juli',
            ],
            [
                'pr_number'    => 'PR-2026-0002',
                'pr_date'      => '2026-07-05',
                'requester'    => 'Siti Rahayu',
                'department'   => 'Gudang',
                'total_items'  => 5,
                'total_qty'    => 500,
                'status'       => 'PENDING',
                'note'         => 'Stok bahan packing menipis',
            ],
            [
                'pr_number'    => 'PR-2026-0003',
                'pr_date'      => '2026-07-10',
                'requester'    => 'Ahmad Hidayat',
                'department'   => 'Produksi',
                'total_items'  => 2,
                'total_qty'    => 75,
                'status'       => 'DRAFT',
                'note'         => 'Permintaan sementara',
            ],
            [
                'pr_number'    => 'PR-2026-0004',
                'pr_date'      => '2026-07-15',
                'requester'    => 'Dewi Lestari',
                'department'   => 'Engineering',
                'total_items'  => 8,
                'total_qty'    => 25,
                'status'       => 'FULFILLED',
                'note'         => 'Sparepart mesin produksi',
            ],
            [
                'pr_number'    => 'PR-2026-0005',
                'pr_date'      => '2026-07-20',
                'requester'    => 'Rudi Hermawan',
                'department'   => 'Produksi',
                'total_items'  => 4,
                'total_qty'    => 200,
                'status'       => 'REJECTED',
                'note'         => 'Permintaan ditolak karena melebihi budget',
            ],
            [
                'pr_number'    => 'PR-2026-0006',
                'pr_date'      => '2026-07-22',
                'requester'    => 'Siti Rahayu',
                'department'   => 'Gudang',
                'total_items'  => 6,
                'total_qty'    => 350,
                'status'       => 'PENDING',
                'note'         => 'Stok bahan kimia habis',
            ],
        ];

        foreach ($data as $item) {
            $this->store->create($item);
        }
    }

    public function index(): View
    {
        return view('material-management.purchase-request.index');
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
                stripos($i['pr_number'] ?? '', $q) !== false ||
                stripos($i['requester'] ?? '', $q) !== false ||
                stripos($i['department'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('pr_date_fmt', fn($row) => \Carbon\Carbon::parse($row['pr_date'])->format('d/m/Y'))
            ->addColumn('total_items', fn($row) => count($row['items'] ?? []))
            ->addColumn('status_badge', function ($row) {
                $map = [
                    'DRAFT'     => ['class' => 'bg-secondary',     'label' => 'Draft'],
                    'PENDING'   => ['class' => 'bg-warning text-dark', 'label' => 'Pending'],
                    'APPROVED'  => ['class' => 'bg-info text-dark',    'label' => 'Approved'],
                    'REJECTED'  => ['class' => 'bg-danger',        'label' => 'Rejected'],
                    'FULFILLED' => ['class' => 'bg-success',       'label' => 'Fulfilled'],
                ];
                $s = $row['status'] ?? 'DRAFT';
                $c = $map[$s]['class'] ?? 'bg-secondary';
                $l = $map[$s]['label'] ?? $s;
                return '<span class="badge ' . $c . '">' . $l . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $row['id'] . '"><i class="bi bi-pencil"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pr_number'  => ['required', 'string', 'max:50'],
            'pr_date'    => ['required', 'date'],
            'requester'  => ['required', 'string', 'max:100'],
            'department' => ['required', 'string', 'max:100'],
            'note'       => ['nullable', 'string'],
            'status'     => ['required', 'string', 'in:DRAFT,PENDING,APPROVED,REJECTED,FULFILLED'],
            'items'      => ['nullable', 'string'],
        ]);

        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $items = array_map(fn($i) => $i + ['qty_fulfilled' => 0], $items);

        $this->store->create($request->only('pr_number', 'pr_date', 'requester', 'department', 'note', 'status') + [
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
            'pr_number'  => ['required', 'string', 'max:50'],
            'pr_date'    => ['required', 'date'],
            'requester'  => ['required', 'string', 'max:100'],
            'department' => ['required', 'string', 'max:100'],
            'note'       => ['nullable', 'string'],
            'status'     => ['required', 'string', 'in:DRAFT,PENDING,APPROVED,REJECTED,FULFILLED'],
            'items'      => ['nullable', 'string'],
        ]);

        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];

        $existing = $this->store->find($id);
        $oldItems = $existing['items'] ?? [];
        $items = array_map(function ($i) use ($oldItems) {
            $match = array_filter($oldItems, fn($o) => ($o['material'] ?? '') === ($i['material'] ?? ''));
            $old = $match ? reset($match) : [];
            return $i + ['qty_fulfilled' => $old['qty_fulfilled'] ?? 0];
        }, $items);

        $this->store->update($id, $request->only('pr_number', 'pr_date', 'requester', 'department', 'note', 'status') + [
            'items' => $items,
        ]);

        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}
