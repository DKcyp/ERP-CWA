<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ProductionSchedulingController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('production-scheduling');
        $this->initDummyData();
        View::share('activeMenu', 'production-scheduling');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $data = [
            [
                'doc_id'          => 'SCH-2026-0001',
                'tipe'            => 'Water Based',
                'fk'              => 'FK-001',
                'date'            => '2026-07-01',
                'spk_no'          => 'SPK-2026-0001',
                'spk_from_date'   => '2026-07-02',
                'spk_to_date'     => '2026-07-05',
                'line_machine_id' => 'LINE-A1',
                'user_id'         => 'USR-001',
                'notes'           => 'Plotting produksi kue kering vanila & cokelat',
                'status'          => 'COMPLETED',
            ],
            [
                'doc_id'          => 'SCH-2026-0002',
                'tipe'            => 'Water Based',
                'fk'              => 'FK-002',
                'date'            => '2026-07-06',
                'spk_no'          => 'SPK-2026-0002',
                'spk_from_date'   => '2026-07-06',
                'spk_to_date'     => '2026-07-10',
                'line_machine_id' => 'LINE-A1',
                'user_id'         => 'USR-002',
                'notes'           => 'Produksi roti gandum, cokelat, keju',
                'status'          => 'IN_PROGRESS',
            ],
            [
                'doc_id'          => 'SCH-2026-0003',
                'tipe'            => 'Solvent Based',
                'fk'              => 'FK-003',
                'date'            => '2026-07-06',
                'spk_no'          => 'SPK-2026-0002',
                'spk_from_date'   => '2026-07-06',
                'spk_to_date'     => '2026-07-10',
                'line_machine_id' => 'LINE-B1',
                'user_id'         => 'USR-002',
                'notes'           => 'Parallel run solvent based batch',
                'status'          => 'IN_PROGRESS',
            ],
            [
                'doc_id'          => 'SCH-2026-0004',
                'tipe'            => 'Water Based',
                'fk'              => 'FK-004',
                'date'            => '2026-07-11',
                'spk_no'          => 'SPK-2026-0003',
                'spk_from_date'   => '2026-07-11',
                'spk_to_date'     => '2026-07-14',
                'line_machine_id' => 'LINE-A2',
                'user_id'         => 'USR-001',
                'notes'           => 'Kue kering vanila & roti gandum',
                'status'          => 'PLANNED',
            ],
            [
                'doc_id'          => 'SCH-2026-0005',
                'tipe'            => 'Solvent Based',
                'fk'              => 'FK-005',
                'date'            => '2026-07-18',
                'spk_no'          => 'SPK-2026-0004',
                'spk_from_date'   => '2026-07-18',
                'spk_to_date'     => '2026-07-22',
                'line_machine_id' => 'LINE-B1',
                'user_id'         => 'USR-003',
                'notes'           => 'Roti gandum & cokelat batch besar',
                'status'          => 'DRAFT',
            ],
            [
                'doc_id'          => 'SCH-2026-0006',
                'tipe'            => 'Water Based',
                'fk'              => 'FK-006',
                'date'            => '2026-07-23',
                'spk_no'          => 'SPK-2026-0005',
                'spk_from_date'   => '2026-07-23',
                'spk_to_date'     => '2026-07-26',
                'line_machine_id' => 'LINE-A1',
                'user_id'         => 'USR-002',
                'notes'           => 'Packing & finishing order cabang',
                'status'          => 'PLANNED',
            ],
            [
                'doc_id'          => 'SCH-2026-0007',
                'tipe'            => 'Water Based',
                'fk'              => 'FK-007',
                'date'            => '2026-07-26',
                'spk_no'          => 'SPK-2026-0006',
                'spk_from_date'   => '2026-07-26',
                'spk_to_date'     => '2026-07-29',
                'line_machine_id' => 'LINE-A2',
                'user_id'         => 'USR-001',
                'notes'           => 'Roti gandum rutin',
                'status'          => 'DRAFT',
            ],
            [
                'doc_id'          => 'SCH-2026-0008',
                'tipe'            => 'Solvent Based',
                'fk'              => 'FK-008',
                'date'            => '2026-07-28',
                'spk_no'          => 'SPK-2026-0007',
                'spk_from_date'   => '2026-07-28',
                'spk_to_date'     => '2026-07-31',
                'line_machine_id' => 'LINE-B2',
                'user_id'         => 'USR-003',
                'notes'           => 'Special batch color matching',
                'status'          => 'DRAFT',
            ],
        ];

        foreach ($data as $item) {
            $this->store->create($item);
        }
    }

    public function index()
    {
        return view('production-planning.production-scheduling.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['doc_id'] ?? '', $q) !== false ||
                stripos($i['spk_no'] ?? '', $q) !== false ||
                stripos($i['line_machine_id'] ?? '', $q) !== false
            );
        }

        if ($request->filled('filter_tipe') && $request->filter_tipe !== 'all') {
            $tipe = $request->filter_tipe;
            $data = array_filter($data, fn($i) => ($i['tipe'] ?? '') === $tipe);
        }

        if ($request->filled('filter_line') && $request->filter_line !== 'all') {
            $line = $request->filter_line;
            $data = array_filter($data, fn($i) => ($i['line_machine_id'] ?? '') === $line);
        }

        if ($request->filled('filter_date_from')) {
            $from = $request->filter_date_from;
            $data = array_filter($data, fn($i) => ($i['spk_from_date'] ?? '') >= $from);
        }
        if ($request->filled('filter_date_to')) {
            $to = $request->filter_date_to;
            $data = array_filter($data, fn($i) => ($i['spk_to_date'] ?? '') <= $to);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('spk_from_fmt', fn($r) => \Carbon\Carbon::parse($r['spk_from_date'])->format('d/m'))
            ->addColumn('spk_to_fmt', fn($r) => \Carbon\Carbon::parse($r['spk_to_date'])->format('d/m'))
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
            'doc_id'          => ['required','string','max:50'],
            'tipe'            => ['required','string','in:Water Based,Solvent Based'],
            'fk'              => ['nullable','string','max:50'],
            'date'            => ['required','date'],
            'spk_no'          => ['required','string','max:50'],
            'spk_from_date'   => ['required','date'],
            'spk_to_date'     => ['required','date'],
            'line_machine_id' => ['required','string','max:50'],
            'user_id'         => ['nullable','string','max:50'],
            'notes'           => ['nullable','string'],
            'status'          => ['required','string','in:DRAFT,PLANNED,IN_PROGRESS,COMPLETED'],
        ]);
        $this->store->create($request->only('doc_id','tipe','fk','date','spk_no','spk_from_date','spk_to_date','line_machine_id','user_id','notes','status'));
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
            'doc_id'          => ['required','string','max:50'],
            'tipe'            => ['required','string','in:Water Based,Solvent Based'],
            'fk'              => ['nullable','string','max:50'],
            'date'            => ['required','date'],
            'spk_no'          => ['required','string','max:50'],
            'spk_from_date'   => ['required','date'],
            'spk_to_date'     => ['required','date'],
            'line_machine_id' => ['required','string','max:50'],
            'user_id'         => ['nullable','string','max:50'],
            'notes'           => ['nullable','string'],
            'status'          => ['required','string','in:DRAFT,PLANNED,IN_PROGRESS,COMPLETED'],
        ]);
        $this->store->update($id, $request->only('doc_id','tipe','fk','date','spk_no','spk_from_date','spk_to_date','line_machine_id','user_id','notes','status'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}