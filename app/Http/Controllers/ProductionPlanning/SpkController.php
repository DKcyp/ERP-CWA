<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SpkController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('spk-list');
        $this->initDummyData();
        View::share('activeMenu', 'spk-list');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $data = [
            [
                'doc_id'            => 'SPK-2026-0001',
                'date'              => '2026-07-02',
                'delivery_date'     => '2026-07-05',
                'warehouse'         => 'Gudang Bahan Jadi',
                'user_id'           => 'USR-001',
                'notes'             => 'SPK dari PRESPK-2026-0001',
                'status'            => 'COMPLETED',
                'items' => [
                    ['product_id' => 'PRD-001', 'product_name' => 'Kue Kering Vanila', 'qty' => 200, 'tonase' => 40, 'qty_needs' => 0, 'tonase_needs' => 0],
                    ['product_id' => 'PRD-002', 'product_name' => 'Kue Kering Cokelat', 'qty' => 150, 'tonase' => 30, 'qty_needs' => 0, 'tonase_needs' => 0],
                ],
            ],
            [
                'doc_id'            => 'SPK-2026-0002',
                'date'              => '2026-07-06',
                'delivery_date'     => '2026-07-10',
                'warehouse'         => 'Gudang Utama',
                'user_id'           => 'USR-002',
                'notes'             => 'SPK dari PRESPK-2026-0002',
                'status'            => 'IN_PROGRESS',
                'items' => [
                    ['product_id' => 'PRD-003', 'product_name' => 'Roti Gandum', 'qty' => 500, 'tonase' => 100, 'qty_needs' => 200, 'tonase_needs' => 40],
                    ['product_id' => 'PRD-004', 'product_name' => 'Roti Cokelat', 'qty' => 300, 'tonase' => 60, 'qty_needs' => 100, 'tonase_needs' => 20],
                    ['product_id' => 'PRD-005', 'product_name' => 'Roti Keju', 'qty' => 200, 'tonase' => 40, 'qty_needs' => 50, 'tonase_needs' => 10],
                ],
            ],
            [
                'doc_id'            => 'SPK-2026-0003',
                'date'              => '2026-07-11',
                'delivery_date'     => '2026-07-14',
                'warehouse'         => 'Gudang Bahan Jadi',
                'user_id'           => 'USR-001',
                'notes'             => 'SPK dari PRESPK-2026-0003',
                'status'            => 'PLANNED',
                'items' => [
                    ['product_id' => 'PRD-001', 'product_name' => 'Kue Kering Vanila', 'qty' => 100, 'tonase' => 20, 'qty_needs' => 100, 'tonase_needs' => 20],
                    ['product_id' => 'PRD-003', 'product_name' => 'Roti Gandum', 'qty' => 250, 'tonase' => 50, 'qty_needs' => 250, 'tonase_needs' => 50],
                ],
            ],
            [
                'doc_id'            => 'SPK-2026-0004',
                'date'              => '2026-07-18',
                'delivery_date'     => '2026-07-22',
                'warehouse'         => 'Gudang Utama',
                'user_id'           => 'USR-003',
                'notes'             => 'SPK dari PRESPK-2026-0005',
                'status'            => 'DRAFT',
                'items' => [
                    ['product_id' => 'PRD-003', 'product_name' => 'Roti Gandum', 'qty' => 400, 'tonase' => 80, 'qty_needs' => 400, 'tonase_needs' => 80],
                    ['product_id' => 'PRD-004', 'product_name' => 'Roti Cokelat', 'qty' => 250, 'tonase' => 50, 'qty_needs' => 250, 'tonase_needs' => 50],
                ],
            ],
            [
                'doc_id'            => 'SPK-2026-0005',
                'date'              => '2026-07-23',
                'delivery_date'     => '2026-07-26',
                'warehouse'         => 'Gudang Cabang',
                'user_id'           => 'USR-002',
                'notes'             => 'SPK dari PRESPK-2026-0006',
                'status'            => 'QC_PENDING',
                'items' => [
                    ['product_id' => 'PRD-001', 'product_name' => 'Kue Kering Vanila', 'qty' => 300, 'tonase' => 60, 'qty_needs' => 50, 'tonase_needs' => 10],
                    ['product_id' => 'PRD-002', 'product_name' => 'Kue Kering Cokelat', 'qty' => 200, 'tonase' => 40, 'qty_needs' => 0, 'tonase_needs' => 0],
                    ['product_id' => 'PRD-005', 'product_name' => 'Roti Keju', 'qty' => 150, 'tonase' => 30, 'qty_needs' => 150, 'tonase_needs' => 30],
                ],
            ],
            [
                'doc_id'            => 'SPK-2026-0006',
                'date'              => '2026-07-26',
                'delivery_date'     => '2026-07-29',
                'warehouse'         => 'Gudang Bahan Jadi',
                'user_id'           => 'USR-001',
                'notes'             => 'SPK baru menunggu konfirmasi',
                'status'            => 'DRAFT',
                'items' => [
                    ['product_id' => 'PRD-003', 'product_name' => 'Roti Gandum', 'qty' => 350, 'tonase' => 70, 'qty_needs' => 350, 'tonase_needs' => 70],
                ],
            ],
        ];

        foreach ($data as $item) {
            $this->store->create($item);
        }
    }

    public function index()
    {
        return view('production-planning.spk-list.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['doc_id'] ?? '', $q) !== false ||
                stripos($i['warehouse'] ?? '', $q) !== false
            );
        }

        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $status = $request->filter_status;
            $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $status);
        }

        if ($request->filled('filter_date_from')) {
            $from = $request->filter_date_from;
            $data = array_filter($data, fn($i) => ($i['date'] ?? '') >= $from);
        }
        if ($request->filled('filter_date_to')) {
            $to = $request->filter_date_to;
            $data = array_filter($data, fn($i) => ($i['date'] ?? '') <= $to);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('delivery_date_fmt', fn($r) => \Carbon\Carbon::parse($r['delivery_date'])->format('d/m/Y'))
            ->addColumn('total_qty', fn($r) => array_sum(array_column($r['items'] ?? [], 'qty')))
            ->addColumn('total_tonase', fn($r) => array_sum(array_column($r['items'] ?? [], 'tonase')))
            ->addColumn('total_qty_needs', fn($r) => array_sum(array_column($r['items'] ?? [], 'qty_needs')))
            ->addColumn('total_tonase_needs', fn($r) => array_sum(array_column($r['items'] ?? [], 'tonase_needs')))
            ->addColumn('status_badge', function ($r) {
                $map = [
                    'DRAFT'       => ['class' => 'bg-secondary',          'label' => 'Draft'],
                    'PLANNED'     => ['class' => 'bg-info text-dark',     'label' => 'Planned'],
                    'IN_PROGRESS' => ['class' => 'bg-primary',            'label' => 'In Progress'],
                    'QC_PENDING'  => ['class' => 'bg-warning text-dark',  'label' => 'QC Pending'],
                    'COMPLETED'   => ['class' => 'bg-success',            'label' => 'Completed'],
                    'REJECTED'    => ['class' => 'bg-danger',             'label' => 'Rejected'],
                ];
                $s = $r['status'] ?? 'DRAFT';
                return '<span class="badge '.($map[$s]['class'] ?? 'bg-secondary').'">'.($map[$s]['label'] ?? $s).'</span>';
            })
            ->addColumn('action', function ($row) {
                $btns = '<div class="btn-group btn-group-sm">';
                $btns .= '<button type="button" class="btn btn-outline-primary btn-edit" data-id="'.$row['id'].'"><i class="bi bi-pencil"></i></button>';
                $btns .= '<button type="button" class="btn btn-outline-danger btn-delete" data-id="'.$row['id'].'"><i class="bi bi-trash"></i></button>';
                $btns .= '</div>';
                return $btns;
            })
            ->rawColumns(['status_badge', 'action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'doc_id'         => ['required','string','max:50'],
            'date'           => ['required','date'],
            'delivery_date'  => ['required','date'],
            'warehouse'      => ['required','string','max:100'],
            'user_id'        => ['nullable','string','max:50'],
            'notes'          => ['nullable','string'],
            'status'         => ['required','string','in:DRAFT,PLANNED,IN_PROGRESS,QC_PENDING,COMPLETED,REJECTED'],
            'items'          => ['nullable','string'],
        ]);
        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $this->store->create($request->only('doc_id','date','delivery_date','warehouse','user_id','notes','status') + ['items' => $items]);
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
            'doc_id'         => ['required','string','max:50'],
            'date'           => ['required','date'],
            'delivery_date'  => ['required','date'],
            'warehouse'      => ['required','string','max:100'],
            'user_id'        => ['nullable','string','max:50'],
            'notes'          => ['nullable','string'],
            'status'         => ['required','string','in:DRAFT,PLANNED,IN_PROGRESS,QC_PENDING,COMPLETED,REJECTED'],
            'items'          => ['nullable','string'],
        ]);
        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $this->store->update($id, $request->only('doc_id','date','delivery_date','warehouse','user_id','notes','status') + ['items' => $items]);
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}