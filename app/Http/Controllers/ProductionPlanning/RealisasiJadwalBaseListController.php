<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class RealisasiJadwalBaseListController extends Controller
{
    protected DummyStore $headerStore;
    protected DummyStore $detailStore;

    public function __construct()
    {
        $this->headerStore = new DummyStore('realisasi-jadwal-base-list');
        $this->detailStore = new DummyStore('realisasi-jadwal-base-list-detail');
        $this->initDummyData();
        View::share('activeMenu', 'realisasi-jadwal-base-list');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->headerStore->all())) return;

        $products = ['Wall Paint White 20L','Wall Paint Cream 10L','Primer Grey 5L','Top Coat Clear 15L','Cat Ekonomis 5L'];
        $machines = ['M-01','M-02','M-03','M-04'];
        $operators = ['Budi Santoso','Andi Kurniawan','Citra Dewi','Dedi Kuswanto','Eka Putri','Fajar Nugroho','Gilang Ramadhan','Hendra Wijaya'];
        $types = ['Water Based','Solvent Based'];
        $statuses = ['Draft','Submitted','Approved','Rejected'];

        $data = [
            ['date'=>'2026-07-28','user_id'=>'oper-001','prod_date'=>'2026-07-28','shift'=>'Shift 1','type'=>'Water Based','status'=>'Approved','notes'=>''],
            ['date'=>'2026-07-28','user_id'=>'oper-002','prod_date'=>'2026-07-28','shift'=>'Shift 2','type'=>'Solvent Based','status'=>'Submitted','notes'=>'Proses normal'],
            ['date'=>'2026-07-29','user_id'=>'oper-001','prod_date'=>'2026-07-29','shift'=>'Shift 1','type'=>'Water Based','status'=>'Approved','notes'=>''],
            ['date'=>'2026-07-29','user_id'=>'oper-003','prod_date'=>'2026-07-29','shift'=>'Shift 1','type'=>'Solvent Based','status'=>'Draft','notes'=>'Menunggu approval'],
            ['date'=>'2026-07-30','user_id'=>'oper-002','prod_date'=>'2026-07-30','shift'=>'Shift 2','type'=>'Water Based','status'=>'Approved','notes'=>''],
            ['date'=>'2026-07-30','user_id'=>'oper-004','prod_date'=>'2026-07-30','shift'=>'Shift 3','type'=>'Solvent Based','status'=>'Rejected','notes'=>'Data tidak lengkap'],
            ['date'=>'2026-07-31','user_id'=>'oper-001','prod_date'=>'2026-07-31','shift'=>'Shift 1','type'=>'Water Based','status'=>'Submitted','notes'=>''],
            ['date'=>'2026-07-31','user_id'=>'oper-003','prod_date'=>'2026-07-31','shift'=>'Shift 2','type'=>'Solvent Based','status'=>'Draft','notes'=>''],
        ];

        $id = 1;
        foreach ($data as $item) {
            $docId = 'RJB-'.date('Ymd', strtotime($item['date'])).'-'.str_pad($id, 3, '0', STR_PAD_LEFT);
            $detailCount = rand(2, 4);
            $totalKg = 0;
            $details = [];
            for ($d = 0; $d < $detailCount; $d++) {
                $basisKg = rand(200, 600);
                $realisasi = $basisKg + rand(-20, 30);
                $totalKg += $realisasi;
                $details[] = [
                    'doc_id' => $docId,
                    'product_name' => $products[array_rand($products)],
                    'batch_no' => 'ADN-'.str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT),
                    'machine' => $machines[array_rand($machines)],
                    'total_basis_kg' => $basisKg,
                    'realisasi_kg' => $realisasi,
                    'jam_mulai' => sprintf('%02d:00', 6 + ($d * 4)),
                    'jam_selesai' => sprintf('%02d:00', 6 + ($d * 4) + 3),
                    'operator' => $operators[array_rand($operators)],
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
        return view('production-planning.realisasi-jadwal-base-list.index');
    }

    public function table(Request $request)
    {
        $data = $this->headerStore->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['doc_id'] ?? '', $q) !== false ||
                stripos($i['user_id'] ?? '', $q) !== false ||
                stripos($i['notes'] ?? '', $q) !== false
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
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required',
            'items.*.realisasi_kg' => 'required|numeric|min:0',
        ]);

        $items = $request->items;
        $totalKg = array_sum(array_column($items, 'realisasi_kg'));

        $headerData = $request->only(['date','user_id','prod_date','shift','type','status','notes']);
        $headerData['total_product_count'] = count($items);
        $headerData['total_realisasi_kg'] = $totalKg;
        $headerData['status'] = $headerData['status'] ?? 'Draft';

        $saved = $this->headerStore->create($headerData);
        $docId = $saved['doc_id'] ?? ('RJB-'.date('Ymd', strtotime($request->date)).'-'.str_pad(substr($saved['id'] ?? '001', -3), 3, '0', STR_PAD_LEFT));

        foreach ($items as $item) {
            $item['doc_id'] = $docId;
            $this->detailStore->create($item);
        }

        return response()->json(['success' => true, 'message' => 'Realisasi jadwal base berhasil disimpan.']);
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
            'items' => 'required|array|min:1',
        ]);

        $header = $this->headerStore->find($id);
        if (!$header) return response()->json(['error' => 'Data tidak ditemukan'], 404);

        $items = $request->items;
        $totalKg = array_sum(array_column($items, 'realisasi_kg'));

        $headerData = $request->only(['date','user_id','prod_date','shift','type','status','notes']);
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