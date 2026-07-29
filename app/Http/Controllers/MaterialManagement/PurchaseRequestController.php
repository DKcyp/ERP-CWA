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
                'note'         => 'Pengadaan bahan baku produksi bulan Juli',
                'status'       => 'APPROVED',
                'items' => [
                    ['material' => 'Tepung Terigu Protein Tinggi', 'qty' => 100, 'unit' => 'Kg', 'required_date' => '2026-07-10', 'qty_ordered' => 100],
                    ['material' => 'Gula Pasir Kristal', 'qty' => 50, 'unit' => 'Kg', 'required_date' => '2026-07-10', 'qty_ordered' => 50],
                    ['material' => 'Mentega Wisman', 'qty' => 30, 'unit' => 'Kg', 'required_date' => '2026-07-12', 'qty_ordered' => 20],
                ],
            ],
            [
                'pr_number'    => 'PR-2026-0002',
                'pr_date'      => '2026-07-05',
                'requester'    => 'Siti Rahayu',
                'department'   => 'Gudang',
                'note'         => 'Stok bahan packing menipis',
                'status'       => 'PENDING',
                'items' => [
                    ['material' => 'Kardus Box 30x20x15', 'qty' => 200, 'unit' => 'Pcs', 'required_date' => '2026-07-15', 'qty_ordered' => 0],
                    ['material' => 'Bubble Wrap 1m', 'qty' => 50, 'unit' => 'Roll', 'required_date' => '2026-07-15', 'qty_ordered' => 0],
                    ['material' => 'Solasi Bening 2 inch', 'qty' => 100, 'unit' => 'Roll', 'required_date' => '2026-07-16', 'qty_ordered' => 0],
                    ['material' => 'Stiker Label Produk', 'qty' => 500, 'unit' => 'Lembar', 'required_date' => '2026-07-18', 'qty_ordered' => 0],
                    ['material' => 'Plastik OPP 30cm', 'qty' => 30, 'unit' => 'Roll', 'required_date' => '2026-07-18', 'qty_ordered' => 0],
                ],
            ],
            [
                'pr_number'    => 'PR-2026-0003',
                'pr_date'      => '2026-07-10',
                'requester'    => 'Ahmad Hidayat',
                'department'   => 'Produksi',
                'note'         => 'Permintaan sementara',
                'status'       => 'DRAFT',
                'items' => [
                    ['material' => 'Pewarna Makanan Merah', 'qty' => 10, 'unit' => 'Liter', 'required_date' => '2026-07-20', 'qty_ordered' => 0],
                    ['material' => 'Perasa Vanila', 'qty' => 5, 'unit' => 'Liter', 'required_date' => '2026-07-20', 'qty_ordered' => 0],
                ],
            ],
            [
                'pr_number'    => 'PR-2026-0004',
                'pr_date'      => '2026-07-15',
                'requester'    => 'Dewi Lestari',
                'department'   => 'Engineering',
                'note'         => 'Sparepart mesin produksi',
                'status'       => 'FULFILLED',
                'items' => [
                    ['material' => 'Bearing 6205-2RS', 'qty' => 10, 'unit' => 'Pcs', 'required_date' => '2026-07-20', 'qty_ordered' => 10],
                    ['material' => 'V-Belt B68', 'qty' => 5, 'unit' => 'Pcs', 'required_date' => '2026-07-20', 'qty_ordered' => 5],
                    ['material' => 'O-Ring 50mm', 'qty' => 20, 'unit' => 'Pcs', 'required_date' => '2026-07-22', 'qty_ordered' => 20],
                    ['material' => 'Grease EP2 18kg', 'qty' => 2, 'unit' => 'Pail', 'required_date' => '2026-07-22', 'qty_ordered' => 2],
                ],
            ],
            [
                'pr_number'    => 'PR-2026-0005',
                'pr_date'      => '2026-07-20',
                'requester'    => 'Rudi Hermawan',
                'department'   => 'Produksi',
                'note'         => 'Permintaan ditolak karena melebihi budget',
                'status'       => 'REJECTED',
                'items' => [
                    ['material' => 'Cokelat Bubuk Van Houten', 'qty' => 25, 'unit' => 'Kg', 'required_date' => '2026-07-28', 'qty_ordered' => 0],
                    ['material' => 'Susu Bubuk Full Cream', 'qty' => 40, 'unit' => 'Kg', 'required_date' => '2026-07-28', 'qty_ordered' => 0],
                    ['material' => 'Telur Ayam', 'qty' => 10, 'unit' => 'Kg', 'required_date' => '2026-07-28', 'qty_ordered' => 0],
                    ['material' => 'Tepung Maizena', 'qty' => 15, 'unit' => 'Kg', 'required_date' => '2026-07-28', 'qty_ordered' => 0],
                ],
            ],
            [
                'pr_number'    => 'PR-2026-0006',
                'pr_date'      => '2026-07-22',
                'requester'    => 'Siti Rahayu',
                'department'   => 'Gudang',
                'note'         => 'Stok bahan kimia habis',
                'status'       => 'PENDING',
                'items' => [
                    ['material' => 'NaOH (Sodium Hydroxide)', 'qty' => 20, 'unit' => 'Kg', 'required_date' => '2026-07-30', 'qty_ordered' => 0],
                    ['material' => 'Citric Acid', 'qty' => 10, 'unit' => 'Kg', 'required_date' => '2026-07-30', 'qty_ordered' => 0],
                    ['material' => 'Pembersih Lantai 5L', 'qty' => 30, 'unit' => 'Liter', 'required_date' => '2026-08-01', 'qty_ordered' => 0],
                    ['material' => 'Hand Sanitizer 500ml', 'qty' => 50, 'unit' => 'Botol', 'required_date' => '2026-08-01', 'qty_ordered' => 0],
                    ['material' => 'Sarung Tangan Karet', 'qty' => 100, 'unit' => 'Pasang', 'required_date' => '2026-08-01', 'qty_ordered' => 0],
                    ['material' => 'Apron Plastik', 'qty' => 20, 'unit' => 'Pcs', 'required_date' => '2026-08-02', 'qty_ordered' => 0],
                ],
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

        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $status = $request->filter_status;
            $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $status);
        }

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['pr_number'] ?? '', $q) !== false ||
                stripos($i['requester'] ?? '', $q) !== false ||
                stripos($i['department'] ?? '', $q) !== false
            );
        }

        if ($request->filled('filter_date_from')) {
            $from = $request->filter_date_from;
            $data = array_filter($data, fn($i) => ($i['pr_date'] ?? '') >= $from);
        }
        if ($request->filled('filter_date_to')) {
            $to = $request->filter_date_to;
            $data = array_filter($data, fn($i) => ($i['pr_date'] ?? '') <= $to);
        }

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('pr_date_fmt', fn($row) => \Carbon\Carbon::parse($row['pr_date'])->format('d/m/Y'))
            ->addColumn('total_items', fn($row) => count($row['items'] ?? []))
            ->addColumn('total_qty_requested', fn($row) => array_sum(array_column($row['items'] ?? [], 'qty')))
            ->addColumn('total_qty_ordered', fn($row) => array_sum(array_column($row['items'] ?? [], 'qty_ordered')))
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
            'pr_number'  => ['required', 'string', 'max:50'],
            'pr_date'    => ['required', 'date'],
            'requester'  => ['required', 'string', 'max:100'],
            'department' => ['required', 'string', 'max:100'],
            'note'       => ['nullable', 'string'],
            'status'     => ['required', 'string', 'in:DRAFT,PENDING,APPROVED,REJECTED,FULFILLED'],
            'items'      => ['nullable', 'string'],
        ]);

        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $items = array_map(fn($i) => $i + ['qty_ordered' => 0], $items);

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
            return $i + ['qty_ordered' => $old['qty_ordered'] ?? 0];
        }, $items);

        $this->store->update($id, $request->only('pr_number', 'pr_date', 'requester', 'department', 'note', 'status') + [
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