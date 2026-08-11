<?php

namespace App\Http\Controllers;

use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class MonitoringPengujianBahanBakuController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('monitoring-pengujian-bahan-baku');
        $this->initDummyData();
        View::share('activeMenu', 'monitoring-pengujian-bahan-baku');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $products = [
            ['id' => 'BB-001', 'name' => 'Resin Alkyd'],
            ['id' => 'BB-002', 'name' => 'Resin Acrylic'],
            ['id' => 'BB-003', 'name' => 'Solvent Toluene'],
            ['id' => 'BB-004', 'name' => 'Pigment Titanium Dioxide'],
            ['id' => 'BB-005', 'name' => 'Aditif Anti Foam'],
            ['id' => 'BB-006', 'name' => 'Pigment Carbon Black'],
            ['id' => 'BB-007', 'name' => 'Solvent Xylene'],
            ['id' => 'BB-008', 'name' => 'Resin Epoxy'],
            ['id' => 'BB-009', 'name' => 'Filler Calcium Carbonate'],
            ['id' => 'BB-010', 'name' => 'Additive Dispersant'],
        ];
        $suppliers = ['PT Kimia Abadi','PT Resin Makmur','PT Pigmen Nusantara','PT Solvent Jaya','PT Aditif Perkasa','PT Bahan Kimia Sentosa'];
        $users = ['Rudi QC','Siti QC','Andi QC','Maya QC','Budi QC'];
        $decisions = ['Approve','Reject','Rework'];

        for ($d = 0; $d < 30; $d++) {
            $dateArr = date('Y-m-d', strtotime("-{$d} days"));
            $count = rand(3, 6);
            for ($i = 0; $i < $count; $i++) {
                $p = $products[array_rand($products)];
                $dec = $decisions[array_rand($decisions)];
                $this->store->create([
                    'product_id' => $p['id'],
                    'product_name' => $p['name'],
                    'batch_number' => 'BB-' . date('ymd', strtotime($dateArr)) . '-' . strtoupper(substr(uniqid(), -4)),
                    'supplier' => $suppliers[array_rand($suppliers)],
                    'tanggal_datang' => date('Y-m-d', strtotime("-" . rand(1, 5) . " days", strtotime($dateArr))),
                    'tanggal_uji' => $dateArr,
                    'user_qc' => $users[array_rand($users)],
                    'solid_content' => round(30 + rand(0, 400) / 10, 1),
                    'viscosity' => round(50 + rand(0, 500) / 10, 1),
                    'ph' => round(6.0 + rand(0, 30) / 10, 1),
                    'specific_gravity' => round(0.8 + rand(0, 60) / 100, 2),
                    'kelembapan' => round(1 + rand(0, 50) / 10, 1),
                    'berat' => round(0.5 + rand(0, 50) / 10, 2),
                    'panjang' => rand(100, 500),
                    'lebar' => rand(50, 200),
                    'appearance' => ['Clear','Milky','Opaque','Crystalline'][rand(0, 3)],
                    'color_visual' => ['#FFFFFF','#FFFF00','#000000','#FF6600','#336699'][rand(0, 4)],
                    'kebersihan' => ['Bersih','Kotor'][rand(0, 1)],
                    'test_gantung' => ['Pass','Fail'][rand(0, 1)],
                    'kualitas_cetak' => ['Good','Fair','Poor'][rand(0, 2)],
                    'kerataan' => ['Rata','Tidak Rata'][rand(0, 1)],
                    'drop_test' => ['Pass','Fail'][rand(0, 1)],
                    'kesimpulan' => $dec === 'Approve' ? 'Bahan baku memenuhi spesifikasi' : ($dec === 'Reject' ? 'Tidak memenuhi standar kualitas' : 'Perlu pengujian ulang'),
                    'keputusan' => $dec,
                    'note' => $dec === 'Reject' ? 'Parameter di luar batas toleransi' : ($dec === 'Rework' ? 'Perlu verifikasi ulang' : '-'),
                ]);
            }
        }
    }

    public function index()
    {
        return view('monitoring-pengujian-bahan-baku');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['product_id'] ?? '', $q) !== false ||
                stripos($i['product_name'] ?? '', $q) !== false ||
                stripos($i['batch_number'] ?? '', $q) !== false ||
                stripos($i['supplier'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_keputusan') && $request->filter_keputusan !== 'all')
            $data = array_filter($data, fn($i) => ($i['keputusan'] ?? '') === $request->filter_keputusan);
        if ($request->filled('filter_date_from'))
            $data = array_filter($data, fn($i) => ($i['tanggal_uji'] ?? '') >= $request->filter_date_from);
        if ($request->filled('filter_date_to'))
            $data = array_filter($data, fn($i) => ($i['tanggal_uji'] ?? '') <= $request->filter_date_to);

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('product_badge', fn($r) => '<strong>' . ($r['product_id'] ?? '-') . '</strong><br><small>' . ($r['product_name'] ?? '-') . '</small>')
            ->addColumn('batch_badge', fn($r) => '<span class="badge bg-secondary">' . ($r['batch_number'] ?? '-') . '</span>')
            ->addColumn('tanggal_uji_fmt', fn($r) => $r['tanggal_uji'] ? \Carbon\Carbon::parse($r['tanggal_uji'])->format('d/m/Y') : '-')
            ->addColumn('keputusan_badge', function ($r) {
                return match($r['keputusan'] ?? '') {
                    'Approve' => '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Approve</span>',
                    'Reject' => '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Reject</span>',
                    'Rework' => '<span class="badge bg-warning text-dark"><i class="bi bi-arrow-repeat me-1"></i>Rework</span>',
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
            ->rawColumns(['product_badge','batch_badge','tanggal_uji_fmt','keputusan_badge','action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:200',
            'batch_number' => 'required|string|max:100',
            'supplier' => 'required|string|max:200',
            'user_qc' => 'required|string|max:100',
            'keputusan' => 'required|string|max:50',
        ]);

        $fields = ['product_id','product_name','batch_number','supplier','tanggal_datang','tanggal_uji','user_qc',
            'solid_content','viscosity','ph','specific_gravity','kelembapan','berat','panjang','lebar',
            'appearance','color_visual','kebersihan','test_gantung','kualitas_cetak','kerataan','drop_test',
            'kesimpulan','keputusan','note',
        ];
        $this->store->create($request->only($fields));
        return response()->json(['success' => true, 'message' => 'Data pengujian bahan baku berhasil disimpan.']);
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

        $request->validate(['product_name' => 'required', 'batch_number' => 'required', 'supplier' => 'required', 'user_qc' => 'required', 'keputusan' => 'required']);

        $fields = ['product_id','product_name','batch_number','supplier','tanggal_datang','tanggal_uji','user_qc',
            'solid_content','viscosity','ph','specific_gravity','kelembapan','berat','panjang','lebar',
            'appearance','color_visual','kebersihan','test_gantung','kualitas_cetak','kerataan','drop_test',
            'kesimpulan','keputusan','note',
        ];
        $this->store->update($id, $request->only($fields));
        return response()->json(['success' => true, 'message' => 'Data pengujian bahan baku berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $this->store->delete($id);
        return response()->json(['success' => true, 'message' => 'Data pengujian bahan baku berhasil dihapus.']);
    }
}
