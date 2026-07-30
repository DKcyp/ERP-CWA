<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class RealisasiJadwalCanningPackingListController extends Controller
{
    protected DummyStore $headerStore;
    protected DummyStore $detailStore;

    public function __construct()
    {
        $this->headerStore = new DummyStore('realisasi-jadwal-canning-packing-list');
        $this->detailStore = new DummyStore('realisasi-jadwal-canning-packing-list-detail');
        $this->initDummyData();
        View::share('activeMenu', 'realisasi-jadwal-canning-packing-list');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->headerStore->all())) return;

        $colors = [
            ['kode'=>'CM-W-001','warna'=>'Putih'],
            ['kode'=>'CM-W-002','warna'=>'Cream'],
            ['kode'=>'CM-P-001','warna'=>'Abu-abu'],
            ['kode'=>'CM-TC-001','warna'=>'Biru'],
            ['kode'=>'CM-E-001','warna'=>'Merah'],
            ['kode'=>'CM-W-003','warna'=>'Hijau'],
        ];
        $operators_canning = ['Rina Sari','Tono Widodo','Siti Aminah','Joko Prasetyo'];
        $operators_packing = ['Maya Putri','Andi Lesmana','Rina Sari','Dedi Kuswanto'];
        $types = ['Water Based','Solvent Based'];
        $categories = ['Pusat','Cabang'];
        $shifts = ['Shift 1','Shift 2','Shift 3'];

        $data = [
            ['date'=>'2026-07-28','user_id'=>'cp-001','prod_date'=>'2026-07-28','shift'=>'Shift 1','type'=>'Water Based','schedule_category'=>'Pusat','status'=>'Approved','notes'=>''],
            ['date'=>'2026-07-28','user_id'=>'cp-002','prod_date'=>'2026-07-28','shift'=>'Shift 2','type'=>'Solvent Based','schedule_category'=>'Cabang','status'=>'Submitted','notes'=>'Proses normal'],
            ['date'=>'2026-07-29','user_id'=>'cp-001','prod_date'=>'2026-07-29','shift'=>'Shift 1','type'=>'Water Based','schedule_category'=>'Pusat','status'=>'Approved','notes'=>''],
            ['date'=>'2026-07-29','user_id'=>'cp-003','prod_date'=>'2026-07-29','shift'=>'Shift 2','type'=>'Solvent Based','schedule_category'=>'Pusat','status'=>'Draft','notes'=>''],
            ['date'=>'2026-07-30','user_id'=>'cp-002','prod_date'=>'2026-07-30','shift'=>'Shift 1','type'=>'Water Based','schedule_category'=>'Cabang','status'=>'Approved','notes'=>''],
            ['date'=>'2026-07-30','user_id'=>'cp-001','prod_date'=>'2026-07-30','shift'=>'Shift 3','type'=>'Solvent Based','schedule_category'=>'Pusat','status'=>'Rejected','notes'=>'Data kemasan tidak lengkap'],
            ['date'=>'2026-07-31','user_id'=>'cp-003','prod_date'=>'2026-07-31','shift'=>'Shift 1','type'=>'Water Based','schedule_category'=>'Cabang','status'=>'Submitted','notes'=>''],
            ['date'=>'2026-07-31','user_id'=>'cp-001','prod_date'=>'2026-07-31','shift'=>'Shift 2','type'=>'Solvent Based','schedule_category'=>'Pusat','status'=>'Draft','notes'=>''],
        ];

        $id = 1;
        foreach ($data as $item) {
            $docId = 'RJCP-'.date('Ymd', strtotime($item['date'])).'-'.str_pad($id, 3, '0', STR_PAD_LEFT);
            $detailCount = rand(2, 4);
            $totalKg = 0;
            $details = [];
            for ($d = 0; $d < $detailCount; $d++) {
                $c = $colors[array_rand($colors)];
                $basis = rand(100, 350);
                $realisasiCm = $basis + rand(-10, 15);
                $realisasiCanning = $realisasiCm + rand(-5, 10);
                $kaleng01 = rand(0, 20); $kaleng02 = rand(0, 30); $kaleng04 = rand(10, 50);
                $kaleng045 = rand(0, 25); $kaleng09 = rand(5, 20); $kalengPcs = rand(0, 15);
                $galon = rand(5, 30); $pail = rand(0, 10); $liter = rand(0, 8);
                $kaleng500 = rand(0, 20); $kaleng1l = rand(0, 15);
                $beratAwal = rand(2000, 5000);
                $beratAkhir = $beratAwal + rand(-50, 50);
                $selisih = $beratAkhir - $beratAwal;
                $totalKg += $realisasiCanning;

                $details[] = [
                    'doc_id' => $docId,
                    'kode_warna' => $c['kode'],
                    'warna' => $c['warna'],
                    'batch_no' => 'CP-ADN-'.str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT),
                    'basis_kg' => $basis,
                    'realisasi_cm_kg' => $realisasiCm,
                    'kaleng_01l' => $kaleng01, 'kaleng_02l' => $kaleng02, 'kaleng_04l' => $kaleng04,
                    'kaleng_045l' => $kaleng045, 'kaleng_09l' => $kaleng09, 'kaleng_pcs' => $kalengPcs,
                    'galon_pcs' => $galon, 'pail_pcs' => $pail, 'liter_pcs' => $liter,
                    'kaleng_500ml' => $kaleng500, 'kaleng_1l' => $kaleng1l,
                    'realisasi_canning_kg' => $realisasiCanning,
                    'tgl_kemas' => $item['date'], 'tgl_selesai' => $item['date'],
                    'sisa_hasil_kemas' => rand(0, 15),
                    'berat_awal' => $beratAwal, 'berat_akhir' => $beratAkhir, 'selisih' => $selisih,
                    'operator_canning' => $operators_canning[array_rand($operators_canning)],
                    'operator_packing' => $operators_packing[array_rand($operators_packing)],
                    'jadwal_ref' => $item['schedule_category'],
                    'keterangan' => '',
                ];
            }
            $item['doc_id'] = $docId;
            $item['total_product_count'] = $detailCount;
            $item['total_realisasi_canning_kg'] = $totalKg;
            $this->headerStore->create($item);
            foreach ($details as $d) { $this->detailStore->create($d); }
            $id++;
        }
    }

    public function index()
    {
        return view('production-planning.realisasi-jadwal-canning-packing-list.index');
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
        if ($request->filled('filter_shift') && $request->filter_shift !== 'all') $data = array_filter($data, fn($i) => ($i['shift'] ?? '') === $request->filter_shift);
        if ($request->filled('filter_type') && $request->filter_type !== 'all') $data = array_filter($data, fn($i) => ($i['type'] ?? '') === $request->filter_type);
        if ($request->filled('filter_category') && $request->filter_category !== 'all') $data = array_filter($data, fn($i) => ($i['schedule_category'] ?? '') === $request->filter_category);

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('prod_date_fmt', fn($r) => \Carbon\Carbon::parse($r['prod_date'])->format('d/m/Y'))
            ->addColumn('realisasi_fmt', fn($r) => number_format($r['total_realisasi_canning_kg'], 0, ',', '.'))
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
            ->rawColumns(['date_fmt','prod_date_fmt','realisasi_fmt','status_badge','action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date', 'prod_date' => 'required|date', 'shift' => 'required',
            'type' => 'required', 'schedule_category' => 'required',
            'items' => 'required|array|min:1',
        ]);

        $items = $request->items;
        $totalKg = array_sum(array_column($items, 'realisasi_canning_kg'));

        $headerData = $request->only(['date','user_id','prod_date','shift','type','schedule_category','status','notes']);
        $headerData['total_product_count'] = count($items);
        $headerData['total_realisasi_canning_kg'] = $totalKg;
        $headerData['status'] = $headerData['status'] ?? 'Draft';

        $saved = $this->headerStore->create($headerData);

        foreach ($items as $item) {
            $item['doc_id'] = $saved['doc_id'] ?? '';
            $this->detailStore->create($item);
        }

        return response()->json(['success' => true, 'message' => 'Realisasi Canning & Packing berhasil disimpan.']);
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
            'date' => 'required|date', 'prod_date' => 'required|date', 'shift' => 'required',
            'type' => 'required', 'schedule_category' => 'required', 'items' => 'required|array|min:1',
        ]);

        $header = $this->headerStore->find($id);
        if (!$header) return response()->json(['error' => 'Data tidak ditemukan'], 404);

        $items = $request->items;
        $headerData = $request->only(['date','user_id','prod_date','shift','type','schedule_category','status','notes']);
        $headerData['total_product_count'] = count($items);
        $headerData['total_realisasi_canning_kg'] = array_sum(array_column($items, 'realisasi_canning_kg'));

        $this->headerStore->update($id, $headerData);

        $docId = $header['doc_id'] ?? '';
        $existing = array_values(array_filter($this->detailStore->all(), fn($d) => ($d['doc_id'] ?? '') !== $docId));
        $this->detailStore->overwriteAll($existing);
        foreach ($items as $item) { $item['doc_id'] = $docId; $this->detailStore->create($item); }

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