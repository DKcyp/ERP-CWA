<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class RealisasiJadwalCMListController extends Controller
{
    protected DummyStore $headerStore;
    protected DummyStore $detailStore;

    public function __construct()
    {
        $this->headerStore = new DummyStore('realisasi-jadwal-cm-list');
        $this->detailStore = new DummyStore('realisasi-jadwal-cm-list-detail');
        $this->initDummyData();
        View::share('activeMenu', 'realisasi-jadwal-cm-list');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->headerStore->all())) return;

        $products = ['CM Wall Paint White 20L','CM Wall Paint Cream 10L','CM Primer Grey 5L','CM Top Coat Blue 15L','CM Cat Ekonomis Red 5L','CM Wall Paint Green 10L'];
        $codes = ['CM-W-001','CM-W-002','CM-P-001','CM-TC-001','CM-E-001','CM-W-003'];
        $machines = ['CM-01','CM-02','CM-03'];
        $operators = ['Rina Sari','Tono Widodo','Siti Aminah','Joko Prasetyo','Maya Putri','Andi Lesmana'];
        $types = ['Water Based','Solvent Based'];
        $categories = ['Pusat','Cabang'];
        $statuses = ['Draft','Submitted','Approved','Rejected'];

        $data = [
            ['date'=>'2026-07-28','user_id'=>'cm-001','prod_date'=>'2026-07-28','shift'=>'Shift 1','type'=>'Water Based','schedule_category'=>'Pusat','status'=>'Approved','notes'=>''],
            ['date'=>'2026-07-28','user_id'=>'cm-002','prod_date'=>'2026-07-28','shift'=>'Shift 2','type'=>'Solvent Based','schedule_category'=>'Cabang','status'=>'Submitted','notes'=>'Proses normal'],
            ['date'=>'2026-07-29','user_id'=>'cm-001','prod_date'=>'2026-07-29','shift'=>'Shift 1','type'=>'Water Based','schedule_category'=>'Pusat','status'=>'Approved','notes'=>''],
            ['date'=>'2026-07-29','user_id'=>'cm-003','prod_date'=>'2026-07-29','shift'=>'Shift 2','type'=>'Solvent Based','schedule_category'=>'Pusat','status'=>'Draft','notes'=>''],
            ['date'=>'2026-07-30','user_id'=>'cm-002','prod_date'=>'2026-07-30','shift'=>'Shift 1','type'=>'Water Based','schedule_category'=>'Cabang','status'=>'Approved','notes'=>''],
            ['date'=>'2026-07-30','user_id'=>'cm-001','prod_date'=>'2026-07-30','shift'=>'Shift 3','type'=>'Solvent Based','schedule_category'=>'Pusat','status'=>'Rejected','notes'=>'Data warna tidak sesuai'],
            ['date'=>'2026-07-31','user_id'=>'cm-003','prod_date'=>'2026-07-31','shift'=>'Shift 1','type'=>'Water Based','schedule_category'=>'Cabang','status'=>'Submitted','notes'=>''],
            ['date'=>'2026-07-31','user_id'=>'cm-001','prod_date'=>'2026-07-31','shift'=>'Shift 2','type'=>'Solvent Based','schedule_category'=>'Pusat','status'=>'Draft','notes'=>''],
        ];

        $id = 1;
        foreach ($data as $item) {
            $docId = 'RJCM-'.date('Ymd', strtotime($item['date'])).'-'.str_pad($id, 3, '0', STR_PAD_LEFT);
            $detailCount = rand(2, 4);
            $totalKg = 0;
            $details = [];
            for ($d = 0; $d < $detailCount; $d++) {
                $pi = array_rand($products);
                $basisKg = rand(100, 400);
                $realisasi = $basisKg + rand(-15, 20);
                $totalKg += $realisasi;
                $details[] = [
                    'doc_id' => $docId,
                    'product_name' => $products[$pi],
                    'kode_warna' => $codes[$pi],
                    'batch_no' => 'CM-ADN-'.str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT),
                    'machine' => $machines[array_rand($machines)],
                    'total_basis_kg' => $basisKg,
                    'realisasi_kg' => $realisasi,
                    'jam_mulai' => sprintf('%02d:00', 6 + ($d * 4)),
                    'jam_selesai' => sprintf('%02d:00', 6 + ($d * 4) + 3),
                    'operator' => $operators[array_rand($operators)],
                    'jadwal_ref' => $item['schedule_category'],
                    'keterangan' => '',
                ];
            }
            $item['doc_id'] = $docId;
            $item['total_product_count'] = $detailCount;
            $item['total_realisasi_kg'] = $totalKg;
            $this->headerStore->create($item);
            foreach ($details as $d) { $this->detailStore->create($d); }
            $id++;
        }
    }

    public function index()
    {
        return view('production-planning.realisasi-jadwal-cm-list.index');
    }

    public function table(Request $request)
    {
        $data = $this->headerStore->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['doc_id'] ?? '', $q) !== false ||
                stripos($i['user_id'] ?? '', $q) !== false ||
                stripos($i['schedule_category'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_date_from')) {
            $from = $request->filter_date_from;
            $data = array_filter($data, fn($i) => ($i['date'] ?? '') >= $from);
        }
        if ($request->filled('filter_date_to')) {
            $to = $request->filter_date_to;
            $data = array_filter($data, fn($i) => ($i['date'] ?? '') <= $to);
        }
        if ($request->filled('filter_shift') && $request->filter_shift !== 'all') {
            $s = $request->filter_shift;
            $data = array_filter($data, fn($i) => ($i['shift'] ?? '') === $s);
        }
        if ($request->filled('filter_type') && $request->filter_type !== 'all') {
            $t = $request->filter_type;
            $data = array_filter($data, fn($i) => ($i['type'] ?? '') === $t);
        }
        if ($request->filled('filter_category') && $request->filter_category !== 'all') {
            $c = $request->filter_category;
            $data = array_filter($data, fn($i) => ($i['schedule_category'] ?? '') === $c);
        }

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('prod_date_fmt', fn($r) => \Carbon\Carbon::parse($r['prod_date'])->format('d/m/Y'))
            ->addColumn('realisasi_fmt', fn($r) => number_format($r['total_realisasi_kg'], 0, ',', '.'))
            ->addColumn('status_badge', function ($r) {
                $s = $r['status'] ?? '';
                return match($s) {
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
            ->rawColumns(['date_fmt','prod_date_fmt','realisasi_fmt','status_badge','action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'prod_date' => 'required|date',
            'shift' => 'required',
            'type' => 'required',
            'schedule_category' => 'required',
            'items' => 'required|array|min:1',
        ]);

        $items = $request->items;
        $totalKg = array_sum(array_column($items, 'realisasi_kg'));

        $headerData = $request->only(['date','user_id','prod_date','shift','type','schedule_category','status','notes']);
        $headerData['total_product_count'] = count($items);
        $headerData['total_realisasi_kg'] = $totalKg;
        $headerData['status'] = $headerData['status'] ?? 'Draft';

        $saved = $this->headerStore->create($headerData);
        $docId = $saved['doc_id'] ?? ('RJCM-'.date('Ymd', strtotime($request->date)).'-'.str_pad(substr($saved['id'] ?? '001', -3), 3, '0', STR_PAD_LEFT));

        foreach ($items as $item) {
            $item['doc_id'] = $docId;
            $this->detailStore->create($item);
        }

        return response()->json(['success' => true, 'message' => 'Realisasi CM berhasil disimpan.']);
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
            'date' => 'required|date',
            'prod_date' => 'required|date',
            'shift' => 'required',
            'type' => 'required',
            'schedule_category' => 'required',
            'items' => 'required|array|min:1',
        ]);

        $header = $this->headerStore->find($id);
        if (!$header) return response()->json(['error' => 'Data tidak ditemukan'], 404);

        $items = $request->items;
        $totalKg = array_sum(array_column($items, 'realisasi_kg'));

        $headerData = $request->only(['date','user_id','prod_date','shift','type','schedule_category','status','notes']);
        $headerData['total_product_count'] = count($items);
        $headerData['total_realisasi_kg'] = $totalKg;

        $this->headerStore->update($id, $headerData);

        $docId = $header['doc_id'] ?? '';
        $existing = array_filter($this->detailStore->all(), fn($d) => ($d['doc_id'] ?? '') !== $docId);
        $this->detailStore->overwriteAll(array_values($existing));

        foreach ($items as $item) {
            $item['doc_id'] = $docId;
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