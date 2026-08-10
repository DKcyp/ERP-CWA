<?php

namespace App\Http\Controllers;

use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class MonitoringSpkpController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('monitoring-spkp');
        $this->initDummyData();
        View::share('activeMenu', 'monitoring-spkp');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $products = [
            ['id' => 'PRD-001', 'name' => 'Wall Paint White 20L'],
            ['id' => 'PRD-002', 'name' => 'Wall Paint Cream 10L'],
            ['id' => 'PRD-003', 'name' => 'Primer Grey 5L'],
            ['id' => 'PRD-004', 'name' => 'Top Coat Clear 15L'],
            ['id' => 'PRD-005', 'name' => 'Cat Ekonomis 5L'],
            ['id' => 'PRD-006', 'name' => 'Thinner A 1L'],
            ['id' => 'PRD-007', 'name' => 'Wood Stain Brown 0.9L'],
        ];
        $users = ['Rudi QC','Siti QC','Andi QC','Maya QC','Budi QC','Lina QC','Fajar QC'];
        $types = ['Base','Base CM','Base Pasta'];
        $decisions = ['Approve Base','Reject Base','Rework ADU'];

        for ($d = 0; $d < 30; $d++) {
            $date = date('Y-m-d', strtotime("-{$d} days"));
            $count = rand(3, 6);
            for ($i = 0; $i < $count; $i++) {
                $p = $products[array_rand($products)];
                $dec = $decisions[array_rand($decisions)];
                $startHour = rand(6, 14);
                $durationHours = rand(2, 8);

                $this->store->create([
                    'product_id' => $p['id'],
                    'product_name' => $p['name'],
                    'type_production' => $types[array_rand($types)],
                    'batch_no' => 'BATCH-' . date('ymd', strtotime($date)) . '-' . strtoupper(substr(uniqid(), -4)),
                    'tgl_mulai' => $date . ' ' . str_pad($startHour, 2, '0', STR_PAD_LEFT) . ':00',
                    'tgl_selesai' => $date . ' ' . str_pad(min($startHour + $durationHours, 23), 2, '0', STR_PAD_LEFT) . ':00',
                    'user_id' => $users[array_rand($users)],
                    'appearance' => ['Clear','Milky','Opaque'][rand(0, 2)],
                    'fineness' => rand(5, 50),
                    'viskositas_ku' => rand(80, 200),
                    'colour' => ['#FFFFFF','#F5F5DC','#808080','#FFD700'][rand(0, 3)],
                    'hiding_power' => round(rand(85, 99) + rand(0, 99) / 100, 2),
                    'sg' => round(0.8 + rand(0, 60) / 100, 2),
                    'ph' => round(6.5 + rand(0, 30) / 10, 1),
                    'solid_content' => round(25 + rand(0, 300) / 10, 1),
                    'viskositas_detik' => rand(20, 120),
                    'viskositas_nk2' => round(20 + rand(0, 200) / 10, 1),
                    'gloss' => round(10 + rand(0, 800) / 10, 1),
                    'miss_print' => ['None','Minor','Major'][rand(0, 2)],
                    'teks' => ['OK','Not OK'][rand(0, 1)],
                    'tampilan' => ['Good','Fair','Poor'][rand(0, 2)],
                    'adhesi' => ['OK','Not OK'][rand(0, 1)],
                    'layout' => ['OK','Not OK'][rand(0, 1)],
                    'kebersihan_kemasan' => ['Bersih','Kotor'][rand(0, 1)],
                    'kualitas_cetakan' => ['Good','Fair','Poor'][rand(0, 2)],
                    'colour_strenght' => round(70 + rand(0, 280) / 10, 1),
                    'ball_test' => ['Pass','Fail'][rand(0, 1)],
                    'matching_test' => ['Pass','Fail'][rand(0, 1)],
                    'drop_test' => ['Pass','Fail'][rand(0, 1)],
                    'cycle_time' => rand(10, 60),
                    'berat' => round(100 + rand(0, 5000) / 10, 1),
                    'dim_tinggi' => rand(100, 300),
                    'dim_atas' => rand(50, 150),
                    'dim_panjang' => rand(100, 400),
                    'dim_diameter_luar' => rand(30, 100),
                    'dim_ring_dalam' => rand(20, 80),
                    'seep_test' => ['Pass','Fail'][rand(0, 1)],
                    'tinggi' => rand(100, 300),
                    'panjang' => rand(100, 400),
                    'lebar' => rand(50, 200),
                    'panjang_lebar_bibir_kuas' => rand(10, 50),
                    'stapler_test' => ['Pass','Fail'][rand(0, 1)],
                    'berat_5_6' => round(50 + rand(0, 200) / 10, 1),
                    'panjang_lebar_bibir_kuas_5_6' => rand(10, 50),
                    'tinggi_5_6' => rand(50, 150),
                    'kualitas_cetakan_2' => ['Good','Fair','Poor'][rand(0, 2)],
                    'stapler_test_4_5' => ['Pass','Fail'][rand(0, 1)],
                    'panjang_5_6' => rand(100, 400),
                    'lebar_5_6' => rand(50, 200),
                    'kesimpulan' => $dec === 'Approve Base' ? 'Memenuhi standar kualitas' : ($dec === 'Reject Base' ? 'Tidak memenuhi spesifikasi' : 'Perlu perbaikan'),
                    'keputusan' => $dec,
                    'note' => $dec === 'Reject Base' ? 'Parameter fisikokimia di luar batas toleransi' : ($dec === 'Rework ADU' ? 'Perlu penyesuaian viskositas' : '-'),
                ]);
            }
        }
    }

    public function index()
    {
        return view('monitoring-spkp');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['product_id'] ?? '', $q) !== false ||
                stripos($i['product_name'] ?? '', $q) !== false ||
                stripos($i['batch_no'] ?? '', $q) !== false ||
                stripos($i['user_id'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_keputusan') && $request->filter_keputusan !== 'all')
            $data = array_filter($data, fn($i) => ($i['keputusan'] ?? '') === $request->filter_keputusan);

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('product_badge', fn($r) => '<strong>' . ($r['product_id'] ?? '-') . '</strong><br><small>' . ($r['product_name'] ?? '-') . '</small>')
            ->addColumn('batch_badge', fn($r) => '<span class="badge bg-secondary">' . ($r['batch_no'] ?? '-') . '</span>')
            ->addColumn('date_range', fn($r) => '<small>Mulai:</small> ' . ($r['tgl_mulai'] ?? '-') . '<br><small>Selesai:</small> ' . ($r['tgl_selesai'] ?? '-'))
            ->addColumn('param_summary', fn($r) => '<small>Fineness:</small> ' . ($r['fineness'] ?? '-') . 'μ<br><small>Visk:</small> ' . ($r['viskositas_ku'] ?? '-') . ' ku<br><small>SG:</small> ' . ($r['sg'] ?? '-') . '<br><small>pH:</small> ' . ($r['ph'] ?? '-'))
            ->addColumn('visual_summary', fn($r) => '<small>Layout:</small> ' . ($r['layout'] ?? '-') . '<br><small>Adhesi:</small> ' . ($r['adhesi'] ?? '-') . '<br><small>Drop:</small> ' . ($r['drop_test'] ?? '-') . '<br><small>Ball:</small> ' . ($r['ball_test'] ?? '-'))
            ->addColumn('keputusan_badge', function ($r) {
                return match($r['keputusan'] ?? '') {
                    'Approve Base' => '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Approve Base</span>',
                    'Reject Base' => '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Reject Base</span>',
                    'Rework ADU' => '<span class="badge bg-warning text-dark"><i class="bi bi-arrow-repeat me-1"></i>Rework ADU</span>',
                    default => '<span class="badge bg-secondary">' . ($r['keputusan'] ?? '-') . '</span>',
                };
            })
            ->addColumn('action', function ($r) {
                return '<div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-info" onclick="detailRecord(\'' . $r['id'] . '\')" title="Detail"><i class="bi bi-eye"></i></button>
                    <button class="btn btn-outline-primary" onclick="editRecord(\'' . $r['id'] . '\')" title="Edit"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-outline-danger" onclick="deleteRecord(\'' . $r['id'] . '\')" title="Hapus"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['product_badge','batch_badge','date_range','param_summary','visual_summary','keputusan_badge','action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:200',
            'batch_no' => 'required|string|max:100',
            'user_id' => 'required|string|max:100',
            'keputusan' => 'required|string|max:50',
        ]);

        $fields = ['product_id','product_name','type_production','batch_no','tgl_mulai','tgl_selesai','user_id','appearance',
            'fineness','viskositas_ku','colour','hiding_power','sg','ph','solid_content','viskositas_detik','viskositas_nk2','gloss',
            'miss_print','teks','tampilan','adhesi','layout','kebersihan_kemasan','kualitas_cetakan','colour_strenght',
            'ball_test','matching_test','drop_test','cycle_time','berat',
            'dim_tinggi','dim_atas','dim_panjang','dim_diameter_luar','dim_ring_dalam',
            'seep_test','tinggi','panjang','lebar','panjang_lebar_bibir_kuas','stapler_test',
            'berat_5_6','panjang_lebar_bibir_kuas_5_6','tinggi_5_6','kualitas_cetakan_2','stapler_test_4_5','panjang_5_6','lebar_5_6',
            'kesimpulan','keputusan','note',
        ];
        $this->store->create($request->only($fields));
        return response()->json(['success' => true, 'message' => 'Data monitoring SPKP berhasil disimpan.']);
    }

    public function show($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);

        $request->validate(['product_name' => 'required', 'batch_no' => 'required', 'user_id' => 'required', 'keputusan' => 'required']);

        $fields = ['product_id','product_name','type_production','batch_no','tgl_mulai','tgl_selesai','user_id','appearance',
            'fineness','viskositas_ku','colour','hiding_power','sg','ph','solid_content','viskositas_detik','viskositas_nk2','gloss',
            'miss_print','teks','tampilan','adhesi','layout','kebersihan_kemasan','kualitas_cetakan','colour_strenght',
            'ball_test','matching_test','drop_test','cycle_time','berat',
            'dim_tinggi','dim_atas','dim_panjang','dim_diameter_luar','dim_ring_dalam',
            'seep_test','tinggi','panjang','lebar','panjang_lebar_bibir_kuas','stapler_test',
            'berat_5_6','panjang_lebar_bibir_kuas_5_6','tinggi_5_6','kualitas_cetakan_2','stapler_test_4_5','panjang_5_6','lebar_5_6',
            'kesimpulan','keputusan','note',
        ];
        $this->store->update($id, $request->only($fields));
        return response()->json(['success' => true, 'message' => 'Data monitoring SPKP berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $this->store->delete($id);
        return response()->json(['success' => true, 'message' => 'Data monitoring SPKP berhasil dihapus.']);
    }
}
