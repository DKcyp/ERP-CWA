<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SPPBJController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('production-sppbj');
        $this->initDummyData();
        View::share('activeMenu', 'production-process');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $products = [
            ['name' => 'Wall Paint White 20L', 'tipe' => 'Emulsi Acrylic', 'formulasi' => 'CM-WP-001', 'basis' => 'Water Based'],
            ['name' => 'Wall Paint Cream 10L', 'tipe' => 'Emulsi Acrylic', 'formulasi' => 'CM-WP-002', 'basis' => 'Water Based'],
            ['name' => 'Primer Grey 5L', 'tipe' => 'Primer', 'formulasi' => 'CM-PR-001', 'basis' => 'Solvent Based'],
            ['name' => 'Top Coat Clear 15L', 'tipe' => 'Top Coat', 'formulasi' => 'CM-TC-001', 'basis' => 'Water Based'],
            ['name' => 'Cat Ekonomis 5L', 'tipe' => 'Economy', 'formulasi' => 'CM-EC-001', 'basis' => 'Water Based'],
        ];

        $machines = ['CM Machine 1','CM Machine 2','CM Machine 3','CM Machine 4'];
        $users = ['Ahmad Hidayat','Dewi Lestari','Rudi Hermawan','Siti Nurhaliza'];
        $decisions = ['Approve','Reject','Rework'];
        $pigments = ['Pasta Putih','Pasta Krem','Pasta Merah','Pasta Kuning','Pasta Hijau','Pasta Biru','Pasta Hitam','Pigment Oxide Red'];

        for ($d = 0; $d < 21; $d++) {
            $date = date('Y-m-d', strtotime("2026-07-10 +{$d} days"));
            $count = rand(2, 4);
            for ($i = 0; $i < $count; $i++) {
                $p = $products[array_rand($products)];
                $batch = 'BN-'.str_pad(rand(401, 500), 4, '0', STR_PAD_LEFT);
                $decision = $decisions[array_rand($decisions)];
                $status = $decision === 'Approve' ? 'Completed' : ($decision === 'Rework' ? 'Rework' : 'Rejected');

                $detailMaterials = [];
                $matCount = rand(3, 6);
                $usedPigments = array_slice($pigments, 0, $matCount);
                foreach ($usedPigments as $m) {
                    $req = rand(10, 100);
                    $detailMaterials[] = [
                        'bahan_baku' => $m,
                        'required_qty' => $req,
                        'production_qty' => $req + rand(-3, 8),
                        'stbj_realization' => $req + rand(-2, 5),
                        'hasil_qc_warna' => rand(85, 100).'%',
                        'adjustment' => rand(-5, 5),
                    ];
                }

                $processStart = date('H:i', strtotime('-'.rand(1,4).' hours'));
                $processEnd = date('H:i', strtotime($processStart.'+'.rand(1,3).' hours'));

                $this->store->create([
                    'production_id' => 'PRD-LST-'.str_pad(rand(1, 17), 4, '0', STR_PAD_LEFT),
                    'jadwal_ref' => 'SPKP-'.date('ymd', strtotime($date)).'-'.str_pad(rand(1, 20), 3, '0', STR_PAD_LEFT),
                    'no_batch' => $batch,
                    'date' => $date,
                    'created_by' => $users[array_rand($users)],
                    'product_name' => $p['name'],
                    'proses_cm' => $processStart,
                    'selesai_cm' => $processEnd,
                    'machine' => $machines[array_rand($machines)],
                    'tipe_produk' => $p['tipe'],
                    'formulasi' => $p['formulasi'],
                    'basis' => $p['basis'],
                    'required_total' => array_sum(array_column($detailMaterials, 'required_qty')),
                    'production_total' => array_sum(array_column($detailMaterials, 'production_qty')),
                    'stbj_total' => array_sum(array_column($detailMaterials, 'stbj_realization')),
                    'adjustment_total' => array_sum(array_column($detailMaterials, 'adjustment')),
                    'notes' => $decision === 'Rework' ? 'Penyesuaian shade warna' : ($decision === 'Reject' ? 'Selisih warna terlalu jauh dari sample' : ''),
                    'keputusan' => $decision,
                    'status_qc' => $status,
                    'items' => $detailMaterials,
                ]);
            }
        }
    }

    public function index()
    {
        return view('production-planning.production-process-sppbj');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['jadwal_ref'] ?? '', $q) !== false ||
                stripos($i['no_batch'] ?? '', $q) !== false ||
                stripos($i['product_name'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_date_from')) $data = array_filter($data, fn($i) => ($i['date'] ?? '') >= $request->filter_date_from);
        if ($request->filled('filter_date_to')) $data = array_filter($data, fn($i) => ($i['date'] ?? '') <= $request->filter_date_to);
        if ($request->filled('filter_keputusan') && $request->filter_keputusan !== 'all') $data = array_filter($data, fn($i) => ($i['keputusan'] ?? '') === $request->filter_keputusan);

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('keputusan_badge', function ($r) {
                return match($r['keputusan'] ?? '') {
                    'Approve' => '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Approve</span>',
                    'Reject' => '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Reject</span>',
                    'Rework' => '<span class="badge bg-warning text-dark"><i class="bi bi-arrow-repeat me-1"></i>Rework</span>',
                    default => '<span class="badge bg-secondary">Draft</span>',
                };
            })
            ->addColumn('action', function ($r) {
                $id = $r['id'];
                $kpt = $r['keputusan'] ?? '';
                $btns = '<div class="btn-group btn-group-sm">';
                $btns .= '<button class="btn btn-outline-info" onclick="detailRecord(\''.$id.'\')" title="Detail"><i class="bi bi-eye"></i></button>';
                $btns .= '<button class="btn btn-outline-primary" onclick="editRecord(\''.$id.'\')" title="Edit"><i class="bi bi-pencil"></i></button>';
                if ($kpt === 'Rework' || $kpt === '' || $kpt === 'Draft') {
                    $btns .= '<button class="btn btn-outline-success" onclick="approveRecord(\''.$id.'\')" title="Approve"><i class="bi bi-check-lg"></i></button>';
                    $btns .= '<button class="btn btn-outline-danger" onclick="rejectRecord(\''.$id.'\')" title="Reject"><i class="bi bi-x-lg"></i></button>';
                }
                $btns .= '<button class="btn btn-outline-danger" onclick="deleteRecord(\''.$id.'\')" title="Hapus"><i class="bi bi-trash"></i></button>';
                $btns .= '</div>';
                return $btns;
            })
            ->rawColumns(['date_fmt','keputusan_badge','action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date', 'product_name' => 'required', 'machine' => 'required',
            'tipe_produk' => 'required', 'keputusan' => 'required',
        ]);

        $data = $request->only(['production_id','jadwal_ref','no_batch','date','created_by','product_name','proses_cm','selesai_cm','machine','tipe_produk','formulasi','basis','required_total','production_total','stbj_total','adjustment_total','notes','keputusan','items']);
        $data['status_qc'] = $data['keputusan'] === 'Approve' ? 'Completed' : ($data['keputusan'] === 'Rework' ? 'Rework' : 'Rejected');
        if (is_string($data['items'] ?? null)) $data['items'] = json_decode($data['items'], true);

        $this->store->create($data);
        return response()->json(['success' => true, 'message' => 'SPPBJ berhasil disimpan.']);
    }

    public function show($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date', 'product_name' => 'required', 'machine' => 'required',
            'tipe_produk' => 'required', 'keputusan' => 'required',
        ]);

        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);

        $data = $request->only(['production_id','jadwal_ref','no_batch','date','created_by','product_name','proses_cm','selesai_cm','machine','tipe_produk','formulasi','basis','required_total','production_total','stbj_total','adjustment_total','notes','keputusan','items']);
        $data['status_qc'] = $data['keputusan'] === 'Approve' ? 'Completed' : ($data['keputusan'] === 'Rework' ? 'Rework' : 'Rejected');
        if (is_string($data['items'] ?? null)) $data['items'] = json_decode($data['items'], true);

        $this->store->update($id, $data);
        return response()->json(['success' => true, 'message' => 'SPPBJ berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $this->store->delete($id);
        return response()->json(['success' => true, 'message' => 'SPPBJ berhasil dihapus.']);
    }

    public function approve($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $this->store->update($id, ['keputusan' => 'Approve', 'status_qc' => 'Completed']);
        return response()->json(['success' => true, 'message' => 'SPPBJ berhasil di-Approve.']);
    }

    public function reject($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $this->store->update($id, ['keputusan' => 'Reject', 'status_qc' => 'Rejected']);
        return response()->json(['success' => true, 'message' => 'SPPBJ berhasil di-Reject.']);
    }
}
