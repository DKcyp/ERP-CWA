<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class JadwalKemasanController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('jadwal-kemasan');
        $this->initDummyData();
        View::share('activeMenu', 'jadwal-kemasan');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $data = [
            [
                'schedule_id'       => 'JK-2026-0001',
                'date'              => '2026-07-28',
                'spk_kemasan_ref'   => 'SPKK-2026-0001',
                'line_packaging_id' => 'PACK-A',
                'product_name'      => 'Wall Paint White 20L',
                'shift'             => 'Shift 1',
                'operator'          => 'Andi Kurniawan',
                'status'            => 'COMPLETED',
                'notes'             => 'Packing kaleng 20L selesai tepat waktu',
                'items' => [
                    ['target_pcs' => 100, 'actual_pcs' => 100],
                ],
            ],
            [
                'schedule_id'       => 'JK-2026-0002',
                'date'              => '2026-07-28',
                'spk_kemasan_ref'   => 'SPKK-2026-0002',
                'line_packaging_id' => 'PACK-B',
                'product_name'      => 'Cat Premium 10L',
                'shift'             => 'Shift 1',
                'operator'          => 'Siti Rahayu',
                'status'            => 'IN_PROGRESS',
                'notes'             => 'Sedang proses packing pail',
                'items' => [
                    ['target_pcs' => 150, 'actual_pcs' => 80],
                ],
            ],
            [
                'schedule_id'       => 'JK-2026-0003',
                'date'              => '2026-07-28',
                'spk_kemasan_ref'   => 'SPKK-2026-0001',
                'line_packaging_id' => 'PACK-A',
                'product_name'      => 'Wall Paint White 20L',
                'shift'             => 'Shift 2',
                'operator'          => 'Budi Santoso',
                'status'            => 'PLANNED',
                'notes'             => 'Lanjutan shift 2',
                'items' => [
                    ['target_pcs' => 100, 'actual_pcs' => 0],
                ],
            ],
            [
                'schedule_id'       => 'JK-2026-0004',
                'date'              => '2026-07-29',
                'spk_kemasan_ref'   => 'SPKK-2026-0003',
                'line_packaging_id' => 'PACK-C',
                'product_name'      => 'Cat Ekonomis 5L',
                'shift'             => 'Shift 1',
                'operator'          => 'Dewi Lestari',
                'status'            => 'DRAFT',
                'notes'             => 'Packing galon 5L',
                'items' => [
                    ['target_pcs' => 250, 'actual_pcs' => 0],
                ],
            ],
            [
                'schedule_id'       => 'JK-2026-0005',
                'date'              => '2026-07-29',
                'spk_kemasan_ref'   => 'SPKK-2026-0004',
                'line_packaging_id' => 'PACK-A',
                'product_name'      => 'Kaleng Custom 15L',
                'shift'             => 'Shift 1',
                'operator'          => 'Ahmad Hidayat',
                'status'            => 'DRAFT',
                'notes'             => 'Pesanan khusus',
                'items' => [
                    ['target_pcs' => 150, 'actual_pcs' => 0],
                ],
            ],
            [
                'schedule_id'       => 'JK-2026-0006',
                'date'              => '2026-07-29',
                'spk_kemasan_ref'   => 'SPKK-2026-0005',
                'line_packaging_id' => 'PACK-B',
                'product_name'      => 'Sample Pail 5L',
                'shift'             => 'Shift 2',
                'operator'          => 'Rudi Hermawan',
                'status'            => 'PLANNED',
                'notes'             => 'Packing sample pail',
                'items' => [
                    ['target_pcs' => 100, 'actual_pcs' => 0],
                ],
            ],
            [
                'schedule_id'       => 'JK-2026-0007',
                'date'              => '2026-07-30',
                'spk_kemasan_ref'   => 'SPKK-2026-0006',
                'line_packaging_id' => 'PACK-A',
                'product_name'      => 'Wall Paint White 20L',
                'shift'             => 'Shift 1',
                'operator'          => 'Andi Kurniawan',
                'status'            => 'DRAFT',
                'notes'             => 'Batch rutin',
                'items' => [
                    ['target_pcs' => 125, 'actual_pcs' => 0],
                ],
            ],
            [
                'schedule_id'       => 'JK-2026-0008',
                'date'              => '2026-07-30',
                'spk_kemasan_ref'   => 'SPKK-2026-0002',
                'line_packaging_id' => 'PACK-C',
                'product_name'      => 'Cat Premium 10L',
                'shift'             => 'Shift 2',
                'operator'          => 'Siti Rahayu',
                'status'            => 'DRAFT',
                'notes'             => 'Lanjutan dari PACK-B',
                'items' => [
                    ['target_pcs' => 150, 'actual_pcs' => 0],
                ],
            ],
        ];

        foreach ($data as $item) {
            $this->store->create($item);
        }
    }

    public function index()
    {
        return view('production-planning.jadwal-kemasan.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['schedule_id'] ?? '', $q) !== false ||
                stripos($i['spk_kemasan_ref'] ?? '', $q) !== false ||
                stripos($i['product_name'] ?? '', $q) !== false ||
                stripos($i['operator'] ?? '', $q) !== false
            );
        }

        if ($request->filled('filter_line') && $request->filter_line !== 'all') {
            $l = $request->filter_line;
            $data = array_filter($data, fn($i) => ($i['line_packaging_id'] ?? '') === $l);
        }

        if ($request->filled('filter_shift') && $request->filter_shift !== 'all') {
            $s = $request->filter_shift;
            $data = array_filter($data, fn($i) => ($i['shift'] ?? '') === $s);
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
            ->addColumn('target_pcs', fn($r) => array_sum(array_column($r['items'] ?? [], 'target_pcs')))
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
            'schedule_id'       => ['required','string','max:50'],
            'date'              => ['required','date'],
            'spk_kemasan_ref'   => ['required','string','max:50'],
            'line_packaging_id' => ['required','string','max:50'],
            'product_name'      => ['required','string','max:200'],
            'shift'             => ['required','string','in:Shift 1,Shift 2,Shift 3'],
            'operator'          => ['required','string','max:100'],
            'status'            => ['required','string','in:DRAFT,PLANNED,IN_PROGRESS,COMPLETED'],
            'notes'             => ['nullable','string'],
            'items'             => ['nullable','string'],
        ]);
        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $this->store->create($request->only('schedule_id','date','spk_kemasan_ref','line_packaging_id','product_name','shift','operator','status','notes') + ['items' => $items]);
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
            'schedule_id'       => ['required','string','max:50'],
            'date'              => ['required','date'],
            'spk_kemasan_ref'   => ['required','string','max:50'],
            'line_packaging_id' => ['required','string','max:50'],
            'product_name'      => ['required','string','max:200'],
            'shift'             => ['required','string','in:Shift 1,Shift 2,Shift 3'],
            'operator'          => ['required','string','max:100'],
            'status'            => ['required','string','in:DRAFT,PLANNED,IN_PROGRESS,COMPLETED'],
            'notes'             => ['nullable','string'],
            'items'             => ['nullable','string'],
        ]);
        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $this->store->update($id, $request->only('schedule_id','date','spk_kemasan_ref','line_packaging_id','product_name','shift','operator','status','notes') + ['items' => $items]);
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}