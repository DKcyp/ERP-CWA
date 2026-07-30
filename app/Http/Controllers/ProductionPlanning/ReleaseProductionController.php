<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ReleaseProductionController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('release-production');
        $this->initDummyData();
        View::share('activeMenu', 'release-production');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $data = [
            [
                'production_id'    => 'PRD-LST-0001','user' => 'Andi Kurniawan','tanggal' => '2026-07-28','status' => 'RELEASED',
                'qc_notes'         => 'Warna sesuai standar, viskositas OK, tidak ada kontaminasi. Lolos semua pengujian.',
                'batch_no'         => 'BN-0421','warehouse_target' => 'Gudang Bahan Jadi',
            ],
            [
                'production_id'    => 'PRD-LST-0002','user' => 'Siti Rahayu','tanggal' => '2026-07-28','status' => 'RELEASED',
                'qc_notes'         => 'Kualitas baik, kemasan utuh. Dapat dirilis.',
                'batch_no'         => 'BN-0422','warehouse_target' => 'Gudang Bahan Jadi',
            ],
            [
                'production_id'    => 'PRD-LST-0005','user' => 'Budi Santoso','tanggal' => '2026-07-30','status' => 'QC_PENDING',
                'qc_notes'         => 'Sedang dalam pengujian batch BN-0425.',
                'batch_no'         => 'BN-0425','warehouse_target' => '',
            ],
            [
                'production_id'    => 'PRD-LST-0003','user' => 'Ahmad Hidayat','tanggal' => '2026-07-29','status' => 'HOLD',
                'qc_notes'         => 'Masih dalam proses grinding, belum selesai.',
                'batch_no'         => 'BN-0423','warehouse_target' => '',
            ],
            [
                'production_id'    => 'PRD-LST-0006','user' => 'Dewi Lestari','tanggal' => '2026-07-27','status' => 'RELEASED',
                'qc_notes'         => 'Primer putih lolos uji. Siap release ke gudang.',
                'batch_no'         => 'BN-0419','warehouse_target' => 'Gudang Utama',
            ],
            [
                'production_id'    => 'PRD-LST-0007','user' => 'Rudi Hermawan','tanggal' => '2026-07-26','status' => 'RELEASED',
                'qc_notes'         => 'Cat ekonomis standar. Selisih masih dalam tolerance.',
                'batch_no'         => 'BN-0417','warehouse_target' => 'Gudang Cabang',
            ],
            [
                'production_id'    => 'PRD-LST-0008','user' => 'Andi Kurniawan','tanggal' => '2026-07-31','status' => 'DRAFT',
                'qc_notes'         => '',
                'batch_no'         => '','warehouse_target' => '',
            ],
        ];

        foreach ($data as $item) {
            $this->store->create($item);
        }
    }

    public function index()
    {
        return view('production-planning.release-production.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['production_id'] ?? '', $q) !== false ||
                stripos($i['batch_no'] ?? '', $q) !== false ||
                stripos($i['user'] ?? '', $q) !== false
            );
        }

        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $s = $request->filter_status;
            $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $s);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('tanggal_fmt', fn($r) => $r['tanggal'] ? \Carbon\Carbon::parse($r['tanggal'])->format('d/m/Y') : '-')
            ->addColumn('status_badge', function ($r) {
                $map = [
                    'DRAFT'       => ['class' => 'bg-secondary',          'label' => 'Draft'],
                    'QC_PENDING'  => ['class' => 'bg-warning text-dark',  'label' => 'QC Pending'],
                    'APPROVED'    => ['class' => 'bg-info text-dark',     'label' => 'Approved'],
                    'HOLD'        => ['class' => 'bg-danger',             'label' => 'Hold'],
                    'REJECTED'    => ['class' => 'bg-dark',               'label' => 'Rejected'],
                    'RELEASED'    => ['class' => 'bg-success',            'label' => 'Released'],
                ];
                $s = $r['status'] ?? 'DRAFT';
                return '<span class="badge '.($map[$s]['class'] ?? 'bg-secondary').'">'.($map[$s]['label'] ?? $s).'</span>';
            })
            ->addColumn('action', function ($row) {
                $id = $row['id'];
                $s = $row['status'] ?? 'DRAFT';
                $btns = '<div class="btn-group btn-group-sm">';
                if (in_array($s, ['QC_PENDING','APPROVED','DRAFT'])) {
                    $btns .= '<button type="button" class="btn btn-outline-success btn-release" data-id="'.$id.'" title="Approve & Release"><i class="bi bi-check-circle"></i></button>';
                    $btns .= '<button type="button" class="btn btn-outline-warning btn-hold" data-id="'.$id.'" title="Hold"><i class="bi bi-pause-circle"></i></button>';
                    $btns .= '<button type="button" class="btn btn-outline-danger btn-reject" data-id="'.$id.'" title="Reject"><i class="bi bi-x-circle"></i></button>';
                }
                $btns .= '<button type="button" class="btn btn-outline-primary btn-detail" data-id="'.$id.'" title="Detail"><i class="bi bi-eye"></i></button>';
                $btns .= '</div>';
                return $btns;
            })
            ->rawColumns(['status_badge', 'action'])->make(true);
    }

    public function show($id)
    {
        $d = $this->store->find($id);
        return $d ? response()->json(['success'=>true,'data'=>$d]) : response()->json(['message'=>'Data tidak ditemukan.'],404);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'           => ['required','string','in:APPROVED,HOLD,REJECTED,RELEASED'],
            'qc_notes'         => ['nullable','string'],
            'warehouse_target' => ['nullable','string','max:100'],
        ]);

        $update = ['status' => $request->status];
        if ($request->filled('qc_notes')) $update['qc_notes'] = $request->qc_notes;
        if ($request->filled('warehouse_target')) $update['warehouse_target'] = $request->warehouse_target;

        $this->store->update($id, $update);
        return response()->json(['message' => 'Status berhasil diperbarui.']);
    }
}