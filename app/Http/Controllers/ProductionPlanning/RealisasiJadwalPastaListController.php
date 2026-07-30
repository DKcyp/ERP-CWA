<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class RealisasiJadwalPastaListController extends Controller
{
    protected DummyStore $headerStore;
    protected DummyStore $detailStore;

    public function __construct()
    {
        $this->headerStore = new DummyStore('realisasi-jadwal-pasta-list');
        $this->detailStore = new DummyStore('realisasi-jadwal-pasta-list-detail');
        $this->initDummyData();
        View::share('activeMenu', 'realisasi-jadwal-pasta-list');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->headerStore->all())) return;

        $pasta = [
            ['kode'=>'PGS-RED-001','name'=>'Pigment Red Oxide'],
            ['kode'=>'PGS-YEL-002','name'=>'Pigment Yellow Ochre'],
            ['kode'=>'PGS-BLU-003','name'=>'Pigment Blue Ultramarine'],
            ['kode'=>'PGS-GRN-004','name'=>'Pigment Green Chrome'],
            ['kode'=>'PGS-WHT-005','name'=>'Pigment White Titanium'],
            ['kode'=>'PGS-BLK-006','name'=>'Pigment Black Carbon'],
            ['kode'=>'PGS-CRM-007','name'=>'Pigment Cream'],
            ['kode'=>'PGS-ORG-008','name'=>'Pigment Orange'],
        ];
        $machines = ['P-01','P-02','P-03'];
        $operators = ['Rina Sari','Tono Widodo','Siti Aminah','Joko Prasetyo','Maya Putri','Andi Lesmana'];
        $types = ['Water Based','Solvent Based'];
        $shifts = ['Shift 1','Shift 2','Shift 3'];
        $pastaStatus = ['Selesai','Proses','Tertunda'];

        $data = [
            ['date'=>'2026-07-28','user_id'=>'pgs-001','type'=>'Water Based','status'=>'Approved','notes'=>''],
            ['date'=>'2026-07-28','user_id'=>'pgs-002','type'=>'Solvent Based','status'=>'Submitted','notes'=>'Proses normal'],
            ['date'=>'2026-07-29','user_id'=>'pgs-001','type'=>'Water Based','status'=>'Approved','notes'=>''],
            ['date'=>'2026-07-29','user_id'=>'pgs-003','type'=>'Solvent Based','status'=>'Draft','notes'=>''],
            ['date'=>'2026-07-30','user_id'=>'pgs-002','type'=>'Water Based','status'=>'Approved','notes'=>''],
            ['date'=>'2026-07-30','user_id'=>'pgs-001','type'=>'Solvent Based','status'=>'Rejected','notes'=>'Lead time melebihi batas'],
            ['date'=>'2026-07-31','user_id'=>'pgs-003','type'=>'Water Based','status'=>'Submitted','notes'=>''],
        ];

        $id = 1;
        foreach ($data as $item) {
            $docId = 'RJP-'.date('Ymd', strtotime($item['date'])).'-'.str_pad($id, 3, '0', STR_PAD_LEFT);
            $detailCount = rand(2, 5);
            $totalKg = 0;
            $details = [];
            for ($d = 0; $d < $detailCount; $d++) {
                $p = $pasta[array_rand($pasta)];
                $basis = rand(50, 250);
                $realisasi = $basis + rand(-10, 15);
                $selisih = $realisasi - $basis;
                $pct = $basis > 0 ? round(($realisasi / $basis) * 100, 1) : 0;
                $totalKg += $realisasi;
                $jadwalDate = date('Y-m-d', strtotime($item['date'] . ' -'.rand(0,2).' days'));
                $deadlineDate = date('Y-m-d', strtotime($jadwalDate.' +'.rand(2,5).' days'));
                $mulaiH = rand(6, 18); $selesaiH = $mulaiH + rand(2, 5);
                $waktuTunggu = rand(0, 120);

                $details[] = [
                    'doc_id' => $docId,
                    'date' => $item['date'],
                    'shift' => $shifts[array_rand($shifts)],
                    'kode_pasta' => $p['kode'],
                    'name_pasta' => $p['name'],
                    'batch_no' => 'PGS-ADN-'.str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT),
                    'machine' => $machines[array_rand($machines)],
                    'tgl_jadwal' => $jadwalDate,
                    'lead_time' => rand(1, 5).' hari',
                    'dateline' => $deadlineDate,
                    'pasta_status' => $pastaStatus[array_rand($pastaStatus)],
                    'total_basis_kg' => $basis,
                    'realisasi_kg' => $realisasi,
                    'selisih_kg' => $selisih,
                    'percentage' => $pct,
                    'mulai' => sprintf('%02d:00', $mulaiH),
                    'selesai' => sprintf('%02d:00', min($selesaiH, 23)),
                    'waktu_tunggu_jam' => intdiv($waktuTunggu, 60),
                    'waktu_tunggu_menit' => $waktuTunggu % 60,
                    'operator' => $operators[array_rand($operators)],
                ];
            }
            $item['doc_id'] = $docId;
            $item['total_pasta_count'] = $detailCount;
            $item['total_realisasi_kg'] = $totalKg;
            $this->headerStore->create($item);
            foreach ($details as $d) { $this->detailStore->create($d); }
            $id++;
        }
    }

    public function index()
    {
        return view('production-planning.realisasi-jadwal-pasta-list.index');
    }

    public function table(Request $request)
    {
        $data = $this->headerStore->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['doc_id'] ?? '', $q) !== false ||
                stripos($i['user_id'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_date_from')) $data = array_filter($data, fn($i) => ($i['date'] ?? '') >= $request->filter_date_from);
        if ($request->filled('filter_date_to')) $data = array_filter($data, fn($i) => ($i['date'] ?? '') <= $request->filter_date_to);
        if ($request->filled('filter_type') && $request->filter_type !== 'all') $data = array_filter($data, fn($i) => ($i['type'] ?? '') === $request->filter_type);

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('realisasi_fmt', fn($r) => number_format($r['total_realisasi_kg'], 0, ',', '.'))
            ->addColumn('status_badge', function ($r) {
                return match($r['status'] ?? '') {
                    'Approved' => '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Approved</span>',
                    'Submitted' => '<span class="badge bg-info"><i class="bi bi-send me-1"></i>Submitted</span>',
                    'Rejected' => '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Rejected</span>',
                    default => '<span class="badge bg-secondary"><i class="bi bi-pencil me-1"></i>Draft</span>',
                };
            })
            ->addColumn('action', function ($r) {
                $id = $r['id'];
                return '<div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" onclick="editRecord(\''.$id.'\')" title="Edit"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-outline-danger" onclick="deleteRecord(\''.$id.'\')" title="Hapus"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['date_fmt','realisasi_fmt','status_badge','action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date', 'type' => 'required',
            'items' => 'required|array|min:1',
        ]);

        $items = $request->items;
        $totalKg = array_sum(array_column($items, 'realisasi_kg'));

        $headerData = $request->only(['date','user_id','type','status','notes']);
        $headerData['total_pasta_count'] = count($items);
        $headerData['total_realisasi_kg'] = $totalKg;
        $headerData['status'] = $headerData['status'] ?? 'Draft';

        $saved = $this->headerStore->create($headerData);

        foreach ($items as $item) {
            $item['doc_id'] = $saved['doc_id'] ?? '';
            $basis = $item['total_basis_kg'] ?? 0;
            $real = $item['realisasi_kg'] ?? 0;
            $item['selisih_kg'] = $real - $basis;
            $item['percentage'] = $basis > 0 ? round(($real / $basis) * 100, 1) : 0;
            $this->detailStore->create($item);
        }

        return response()->json(['success' => true, 'message' => 'Realisasi Pasta berhasil disimpan.']);
    }

    public function show($id)
    {
        $header = $this->headerStore->find($id);
        if (!$header) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $details = array_values(array_filter($this->detailStore->all(), fn($d) => ($d['doc_id'] ?? '') === ($header['doc_id'] ?? '')));
        $header['items'] = $details;
        return response()->json($header);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date', 'type' => 'required', 'items' => 'required|array|min:1',
        ]);

        $header = $this->headerStore->find($id);
        if (!$header) return response()->json(['error' => 'Data tidak ditemukan'], 404);

        $items = $request->items;
        $headerData = $request->only(['date','user_id','type','status','notes']);
        $headerData['total_pasta_count'] = count($items);
        $headerData['total_realisasi_kg'] = array_sum(array_column($items, 'realisasi_kg'));

        $this->headerStore->update($id, $headerData);

        $docId = $header['doc_id'] ?? '';
        $existing = array_values(array_filter($this->detailStore->all(), fn($d) => ($d['doc_id'] ?? '') !== $docId));
        $this->detailStore->overwriteAll($existing);
        foreach ($items as $item) {
            $item['doc_id'] = $docId;
            $basis = $item['total_basis_kg'] ?? 0;
            $real = $item['realisasi_kg'] ?? 0;
            $item['selisih_kg'] = $real - $basis;
            $item['percentage'] = $basis > 0 ? round(($real / $basis) * 100, 1) : 0;
            $this->detailStore->create($item);
        }

        return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $header = $this->headerStore->find($id);
        if (!$header) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $docId = $header['doc_id'] ?? '';
        $remaining = array_values(array_filter($this->detailStore->all(), fn($d) => ($d['doc_id'] ?? '') !== $docId));
        $this->detailStore->overwriteAll($remaining);
        $this->headerStore->delete($id);
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
    }
}