<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SPKPController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('production-spkp');
        $this->initDummyData();
        View::share('activeMenu', 'production-process');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $products = [
            ['name' => 'Wall Paint White 20L', 'tipe' => 'Emulsi Acrylic', 'formulasi' => 'F-WP-001', 'fk' => 'FK-101', 'basis' => 'Water Based'],
            ['name' => 'Wall Paint Cream 10L', 'tipe' => 'Emulsi Acrylic', 'formulasi' => 'F-WP-002', 'fk' => 'FK-102', 'basis' => 'Water Based'],
            ['name' => 'Primer Grey 5L', 'tipe' => 'Primer', 'formulasi' => 'F-PR-001', 'fk' => 'FK-201', 'basis' => 'Solvent Based'],
            ['name' => 'Top Coat Clear 15L', 'tipe' => 'Top Coat', 'formulasi' => 'F-TC-001', 'fk' => 'FK-301', 'basis' => 'Water Based'],
            ['name' => 'Cat Ekonomis 5L', 'tipe' => 'Economy', 'formulasi' => 'F-EC-001', 'fk' => 'FK-401', 'basis' => 'Water Based'],
        ];

        $machines = ['Mixer A-1','Mixer A-2','Mixer B-1','Mixer B-2','Mixer C-1'];
        $users = ['Ahmad Hidayat','Dewi Lestari','Rudi Hermawan','Siti Nurhaliza'];
        $decisions = ['Approve','Reject','Rework'];
        $materials = ['Resin Acrylic','Titanium Dioxide','Talc Powder','Pigment','Defoamer','Thinner','Water','Wax Emulsion'];

        for ($d = 0; $d < 21; $d++) {
            $date = date('Y-m-d', strtotime("2026-07-10 +{$d} days"));
            $count = rand(2, 4);
            for ($i = 0; $i < $count; $i++) {
                $p = $products[array_rand($products)];
                $batch = 'BN-'.str_pad(rand(401, 500), 4, '0', STR_PAD_LEFT);
                $decision = $decisions[array_rand($decisions)];
                $status = $decision === 'Approve' ? 'Completed' : ($decision === 'Rework' ? 'Rework' : 'Rejected');

                $detailMaterials = [];
                $matCount = rand(4, 7);
                $usedMats = array_slice($materials, 0, $matCount);
                foreach ($usedMats as $m) {
                    $req = rand(50, 500);
                    $detailMaterials[] = [
                        'material_name' => $m,
                        'required_qty' => $req,
                        'recanning' => $req + rand(-5, 5),
                        'production_qty' => $req + rand(-3, 8),
                        'stbj_realization' => $req + rand(-2, 5),
                        'qc_check' => rand(85, 100).'%',
                        'adjustment' => rand(-10, 10),
                    ];
                }

                $created = date('H:i', strtotime('-'.rand(1,5).' hours', strtotime($date)));
                $processStart = date('H:i', strtotime($created.'+'.'30 minutes'));
                $processEnd = date('H:i', strtotime($processStart.'+'.rand(1,3).' hours'));

                $this->store->create([
                    'production_id' => 'PRD-LST-'.str_pad(rand(1, 17), 4, '0', STR_PAD_LEFT),
                    'jadwal_ref' => 'JWL-'.date('ymd', strtotime($date)).'-'.str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                    'no_spkp' => 'SPKP-'.date('ymd', strtotime($date)).'-'.str_pad($d * 4 + $i + 1, 3, '0', STR_PAD_LEFT),
                    'batch_no' => $batch,
                    'date' => $date,
                    'created_by' => $users[array_rand($users)],
                    'product_name' => $p['name'],
                    'process_base' => $processStart,
                    'selesai_base' => $processEnd,
                    'machine' => $machines[array_rand($machines)],
                    'tipe_produk' => $p['tipe'],
                    'formulasi' => $p['formulasi'],
                    'fk' => $p['fk'],
                    'basis' => $p['basis'],
                    'required_total' => array_sum(array_column($detailMaterials, 'required_qty')),
                    'recanning_total' => array_sum(array_column($detailMaterials, 'recanning')),
                    'production_total' => array_sum(array_column($detailMaterials, 'production_qty')),
                    'stbj_total' => array_sum(array_column($detailMaterials, 'stbj_realization')),
                    'adjustment_total' => array_sum(array_column($detailMaterials, 'adjustment')),
                    'notes' => $decision === 'Rework' ? 'Perlu penyesuaian viskositas' : ($decision === 'Reject' ? 'Kontaminasi warna terdeteksi' : ''),
                    'keputusan' => $decision,
                    'status_qc' => $status,
                    'items' => $detailMaterials,
                ]);
            }
        }
    }

    public function index()
    {
        return view('production-planning.production-process-spkp');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['no_spkp'] ?? '', $q) !== false ||
                stripos($i['batch_no'] ?? '', $q) !== false ||
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
                $btns = '<div class="btn-group btn-group-sm">';
                $btns .= '<button class="btn btn-outline-primary" onclick="editRecord(\''.$id.'\')" title="Edit"><i class="bi bi-pencil"></i></button>';
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

        $data = $request->only(['production_id','jadwal_ref','no_spkp','batch_no','date','created_by','product_name','process_base','selesai_base','machine','tipe_produk','formulasi','fk','basis','required_total','recanning_total','production_total','stbj_total','adjustment_total','notes','keputusan','items']);
        $data['status_qc'] = $data['keputusan'] === 'Approve' ? 'Completed' : ($data['keputusan'] === 'Rework' ? 'Rework' : 'Rejected');
        if (is_string($data['items'] ?? null)) $data['items'] = json_decode($data['items'], true);

        $this->store->create($data);
        return response()->json(['success' => true, 'message' => 'SPKP berhasil disimpan.']);
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

        $data = $request->only(['production_id','jadwal_ref','no_spkp','batch_no','date','created_by','product_name','process_base','selesai_base','machine','tipe_produk','formulasi','fk','basis','required_total','recanning_total','production_total','stbj_total','adjustment_total','notes','keputusan','items']);
        $data['status_qc'] = $data['keputusan'] === 'Approve' ? 'Completed' : ($data['keputusan'] === 'Rework' ? 'Rework' : 'Rejected');
        if (is_string($data['items'] ?? null)) $data['items'] = json_decode($data['items'], true);

        $this->store->update($id, $data);
        return response()->json(['success' => true, 'message' => 'SPKP berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $this->store->delete($id);
        return response()->json(['success' => true, 'message' => 'SPKP berhasil dihapus.']);
    }
}
