<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SpkKemasanController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('spk-kemasan');
        $this->initDummyData();
        View::share('activeMenu', 'spk-kemasan');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $data = [
            [
                'doc_id'            => 'SPKK-2026-0001',
                'date'              => '2026-07-01',
                'spk_ref_no'        => 'SPK-2026-0001',
                'product_id'        => 'PRD-001',
                'package_type'      => 'Kaleng',
                'status'            => 'COMPLETED',
                'notes'             => 'Kemasan kaleng 20L untuk Wall Paint',
                'user_id'           => 'USR-001',
                'items' => [
                    ['spec_name' => 'Kaleng 20L', 'spec_qty' => 200, 'spec_uom' => 'Pcs', 'material' => 'Kaleng Blank 20L', 'tonase' => 40],
                    ['spec_name' => 'Tutup Kaleng', 'spec_qty' => 200, 'spec_uom' => 'Pcs', 'material' => 'Tutup Metal 20L', 'tonase' => 5],
                    ['spec_name' => 'Label Produk', 'spec_qty' => 200, 'spec_uom' => 'Lembar', 'material' => 'Label Vinyl', 'tonase' => 0.5],
                ],
            ],
            [
                'doc_id'            => 'SPKK-2026-0002',
                'date'              => '2026-07-05',
                'spk_ref_no'        => 'SPK-2026-0002',
                'product_id'        => 'PRD-003',
                'package_type'      => 'Pail',
                'status'            => 'IN_PROGRESS',
                'notes'             => 'Pail 10L untuk cat premium',
                'user_id'           => 'USR-002',
                'items' => [
                    ['spec_name' => 'Pail 10L', 'spec_qty' => 300, 'spec_uom' => 'Pcs', 'material' => 'Pail Plastic 10L', 'tonase' => 30],
                    ['spec_name' => 'Tutup Pail', 'spec_qty' => 300, 'spec_uom' => 'Pcs', 'material' => 'Tutup Plastic 10L', 'tonase' => 3],
                    ['spec_name' => 'Handle Pail', 'spec_qty' => 300, 'spec_uom' => 'Pcs', 'material' => 'Handle Wire', 'tonase' => 1.5],
                    ['spec_name' => 'Label Pail', 'spec_qty' => 300, 'spec_uom' => 'Lembar', 'material' => 'Label Premium', 'tonase' => 0.8],
                ],
            ],
            [
                'doc_id'            => 'SPKK-2026-0003',
                'date'              => '2026-07-10',
                'spk_ref_no'        => 'SPK-2026-0003',
                'product_id'        => 'PRD-005',
                'package_type'      => 'Galon',
                'status'            => 'PLANNED',
                'notes'             => 'Galon 5L untuk cat ekonomis',
                'user_id'           => 'USR-001',
                'items' => [
                    ['spec_name' => 'Galon 5L', 'spec_qty' => 500, 'spec_uom' => 'Pcs', 'material' => 'Galon HDPE 5L', 'tonase' => 25],
                    ['spec_name' => 'Tutup Galon', 'spec_qty' => 500, 'spec_uom' => 'Pcs', 'material' => 'Tutup Flip-top', 'tonase' => 2],
                    ['spec_name' => 'Label Galon', 'spec_qty' => 500, 'spec_uom' => 'Lembar', 'material' => 'Label Economical', 'tonase' => 0.6],
                ],
            ],
            [
                'doc_id'            => 'SPKK-2026-0004',
                'date'              => '2026-07-15',
                'spk_ref_no'        => 'SPK-2026-0004',
                'product_id'        => 'PRD-002',
                'package_type'      => 'Kaleng',
                'status'            => 'DRAFT',
                'notes'             => 'Kaleng 15L untuk pesanan khusus',
                'user_id'           => 'USR-003',
                'items' => [
                    ['spec_name' => 'Kaleng 15L', 'spec_qty' => 150, 'spec_uom' => 'Pcs', 'material' => 'Kaleng Blank 15L', 'tonase' => 22.5],
                    ['spec_name' => 'Tutup Kaleng', 'spec_qty' => 150, 'spec_uom' => 'Pcs', 'material' => 'Tutup Metal 15L', 'tonase' => 3],
                ],
            ],
            [
                'doc_id'            => 'SPKK-2026-0005',
                'date'              => '2026-07-20',
                'spk_ref_no'        => 'SPK-2026-0005',
                'product_id'        => 'PRD-004',
                'package_type'      => 'Pail',
                'status'            => 'PLANNED',
                'notes'             => 'Pail 5L untuk sample produk',
                'user_id'           => 'USR-002',
                'items' => [
                    ['spec_name' => 'Pail 5L', 'spec_qty' => 100, 'spec_uom' => 'Pcs', 'material' => 'Pail Plastic 5L', 'tonase' => 5],
                    ['spec_name' => 'Tutup Pail', 'spec_qty' => 100, 'spec_uom' => 'Pcs', 'material' => 'Tutup Plastic 5L', 'tonase' => 0.5],
                    ['spec_name' => 'Label Sample', 'spec_qty' => 100, 'spec_uom' => 'Lembar', 'material' => 'Label Sample', 'tonase' => 0.1],
                ],
            ],
            [
                'doc_id'            => 'SPKK-2026-0006',
                'date'              => '2026-07-25',
                'spk_ref_no'        => 'SPK-2026-0006',
                'product_id'        => 'PRD-001',
                'package_type'      => 'Kaleng',
                'status'            => 'DRAFT',
                'notes'             => 'Kaleng 20L batch produksi rutin',
                'user_id'           => 'USR-001',
                'items' => [
                    ['spec_name' => 'Kaleng 20L', 'spec_qty' => 250, 'spec_uom' => 'Pcs', 'material' => 'Kaleng Blank 20L', 'tonase' => 50],
                    ['spec_name' => 'Tutup Kaleng', 'spec_qty' => 250, 'spec_uom' => 'Pcs', 'material' => 'Tutup Metal 20L', 'tonase' => 6],
                    ['spec_name' => 'Label Produk', 'spec_qty' => 250, 'spec_uom' => 'Lembar', 'material' => 'Label Vinyl', 'tonase' => 0.6],
                ],
            ],
        ];

        foreach ($data as $item) {
            $this->store->create($item);
        }
    }

    public function index()
    {
        return view('production-planning.spk-kemasan.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['doc_id'] ?? '', $q) !== false ||
                stripos($i['spk_ref_no'] ?? '', $q) !== false ||
                stripos($i['product_id'] ?? '', $q) !== false
            );
        }

        if ($request->filled('filter_package') && $request->filter_package !== 'all') {
            $p = $request->filter_package;
            $data = array_filter($data, fn($i) => ($i['package_type'] ?? '') === $p);
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
            ->addColumn('total_qty_pcs', fn($r) => array_sum(array_column($r['items'] ?? [], 'spec_qty')))
            ->addColumn('total_tonase', fn($r) => number_format(array_sum(array_column($r['items'] ?? [], 'tonase')),2,',','.'))
            ->addColumn('status_badge', function ($r) {
                $map = [
                    'DRAFT'       => ['class' => 'bg-secondary',          'label' => 'Draft'],
                    'PLANNED'     => ['class' => 'bg-info text-dark',     'label' => 'Planned'],
                    'IN_PROGRESS' => ['class' => 'bg-primary',            'label' => 'In Progress'],
                    'COMPLETED'   => ['class' => 'bg-success',            'label' => 'Completed'],
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
            'doc_id'        => ['required','string','max:50'],
            'date'          => ['required','date'],
            'spk_ref_no'    => ['required','string','max:50'],
            'product_id'    => ['required','string','max:50'],
            'package_type'  => ['required','string','in:Kaleng,Galon,Pail'],
            'status'        => ['required','string','in:DRAFT,PLANNED,IN_PROGRESS,COMPLETED'],
            'notes'         => ['nullable','string'],
            'user_id'       => ['nullable','string','max:50'],
            'items'         => ['nullable','string'],
        ]);
        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $this->store->create($request->only('doc_id','date','spk_ref_no','product_id','package_type','status','notes','user_id') + ['items' => $items]);
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
            'doc_id'        => ['required','string','max:50'],
            'date'          => ['required','date'],
            'spk_ref_no'    => ['required','string','max:50'],
            'product_id'    => ['required','string','max:50'],
            'package_type'  => ['required','string','in:Kaleng,Galon,Pail'],
            'status'        => ['required','string','in:DRAFT,PLANNED,IN_PROGRESS,COMPLETED'],
            'notes'         => ['nullable','string'],
            'user_id'       => ['nullable','string','max:50'],
            'items'         => ['nullable','string'],
        ]);
        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $this->store->update($id, $request->only('doc_id','date','spk_ref_no','product_id','package_type','status','notes','user_id') + ['items' => $items]);
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}