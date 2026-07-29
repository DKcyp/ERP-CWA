<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class StockTransferRequestController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('stock-transfer-request-list');
        $this->initDummyData();
        View::share('activeMenu', 'stock-transfer-request-list');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $data = [
            [
                'request_no'          => 'STR-2026-0001',
                'date'                => '2026-07-01',
                'requester_warehouse' => 'Gudang Utama',
                'source_warehouse'    => 'Gudang Cabang',
                'reason'              => 'Pemindahan stok bahan baku ke gudang cabang',
                'status'              => 'APPROVED',
                'items' => [
                    ['material' => 'Tepung Terigu Protein Tinggi', 'qty' => 50],
                    ['material' => 'Gula Pasir Kristal', 'qty' => 30],
                ],
            ],
            [
                'request_no'          => 'STR-2026-0002',
                'date'                => '2026-07-05',
                'requester_warehouse' => 'Gudang Bahan Jadi',
                'source_warehouse'    => 'Gudang Utama',
                'reason'              => 'Restock produk jadi untuk penjualan',
                'status'              => 'PENDING',
                'items' => [
                    ['material' => 'Kue Kering Vanila', 'qty' => 100],
                    ['material' => 'Kue Kering Cokelat', 'qty' => 80],
                    ['material' => 'Roti Gandum', 'qty' => 60],
                ],
            ],
            [
                'request_no'          => 'STR-2026-0003',
                'date'                => '2026-07-10',
                'requester_warehouse' => 'Gudang Bahan Kimia',
                'source_warehouse'    => 'Gudang Utama',
                'reason'              => 'Transfer bahan pembersih ke gudang khusus',
                'status'              => 'DRAFT',
                'items' => [
                    ['material' => 'NaOH (Sodium Hydroxide)', 'qty' => 20],
                    ['material' => 'Citric Acid', 'qty' => 10],
                ],
            ],
            [
                'request_no'          => 'STR-2026-0004',
                'date'                => '2026-07-15',
                'requester_warehouse' => 'Gudang Cabang',
                'source_warehouse'    => 'Gudang Bahan Jadi',
                'reason'              => 'Pemindahan produk jadi ke cabang',
                'status'              => 'FULFILLED',
                'items' => [
                    ['material' => 'Kue Kering Vanila', 'qty' => 200],
                    ['material' => 'Roti Gandum', 'qty' => 100],
                ],
            ],
            [
                'request_no'          => 'STR-2026-0005',
                'date'                => '2026-07-20',
                'requester_warehouse' => 'Gudang Utama',
                'source_warehouse'    => 'Gudang Cabang',
                'reason'              => 'Pengembalian sisa stok dari cabang',
                'status'              => 'REJECTED',
                'items' => [
                    ['material' => 'Kue Kering Cokelat', 'qty' => 50],
                ],
            ],
            [
                'request_no'          => 'STR-2026-0006',
                'date'                => '2026-07-25',
                'requester_warehouse' => 'Gudang Bahan Jadi',
                'source_warehouse'    => 'Gudang Utama',
                'reason'              => 'Stok produk jadi menipis, perlu restock',
                'status'              => 'PENDING',
                'items' => [
                    ['material' => 'Kue Kering Vanila', 'qty' => 150],
                    ['material' => 'Kue Kering Cokelat', 'qty' => 120],
                    ['material' => 'Roti Gandum', 'qty' => 80],
                    ['material' => 'Roti Cokelat', 'qty' => 70],
                ],
            ],
        ];

        foreach ($data as $item) {
            $this->store->create($item);
        }
    }

    public function index()
    {
        return view('material-management.stock-transfer-request-list.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['request_no'] ?? '', $q) !== false ||
                stripos($i['requester_warehouse'] ?? '', $q) !== false ||
                stripos($i['source_warehouse'] ?? '', $q) !== false
            );
        }

        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $status = $request->filter_status;
            $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $status);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('total_items', fn($r) => count($r['items'] ?? []))
            ->addColumn('status_badge', function ($r) {
                $map = [
                    'DRAFT'     => ['class' => 'bg-secondary',          'label' => 'Draft'],
                    'PENDING'   => ['class' => 'bg-warning text-dark',  'label' => 'Pending'],
                    'APPROVED'  => ['class' => 'bg-info text-dark',     'label' => 'Approved'],
                    'REJECTED'  => ['class' => 'bg-danger',             'label' => 'Rejected'],
                    'FULFILLED' => ['class' => 'bg-success',            'label' => 'Fulfilled'],
                ];
                $s = $r['status'] ?? 'DRAFT';
                $c = $map[$s]['class'] ?? 'bg-secondary';
                $l = $map[$s]['label'] ?? $s;
                return '<span class="badge ' . $c . '">' . $l . '</span>';
            })
            ->addColumn('action', function ($row) {
                $id = $row['id'];
                $btns = '<div class="btn-group btn-group-sm">';
                $btns .= '<button type="button" class="btn btn-outline-primary btn-edit" data-id="'.$id.'"><i class="bi bi-pencil"></i></button>';
                $btns .= '<button type="button" class="btn btn-outline-danger btn-delete" data-id="'.$id.'"><i class="bi bi-trash"></i></button>';
                $btns .= '</div>';
                return $btns;
            })
            ->rawColumns(['status_badge', 'action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'request_no'          => ['required','string','max:50'],
            'date'                => ['required','date'],
            'requester_warehouse' => ['required','string','max:100'],
            'source_warehouse'    => ['required','string','max:100'],
            'reason'              => ['nullable','string'],
            'status'              => ['required','string','in:DRAFT,PENDING,APPROVED,REJECTED,FULFILLED'],
            'items'               => ['nullable','string'],
        ]);
        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $this->store->create($request->only('request_no','date','requester_warehouse','source_warehouse','reason','status') + ['items' => $items]);
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    public function show($id)
    {
        $d = $this->store->find($id);
        return $d ? response()->json(['success'=>true,'data'=>$d]) : response()->json(['message'=>'Data tidak ditemukan.'],404);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'request_no'          => ['required','string','max:50'],
            'date'                => ['required','date'],
            'requester_warehouse' => ['required','string','max:100'],
            'source_warehouse'    => ['required','string','max:100'],
            'reason'              => ['nullable','string'],
            'status'              => ['required','string','in:DRAFT,PENDING,APPROVED,REJECTED,FULFILLED'],
            'items'               => ['nullable','string'],
        ]);
        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $this->store->update($id, $request->only('request_no','date','requester_warehouse','source_warehouse','reason','status') + ['items' => $items]);
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}