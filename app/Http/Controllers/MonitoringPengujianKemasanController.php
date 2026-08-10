<?php

namespace App\Http\Controllers;

use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class MonitoringPengujianKemasanController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('monitoring-pengujian-kemasan');
        $this->initDummyData();
        View::share('activeMenu', 'monitoring-pengujian-kemasan');
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
        $users = ['Rudi QC','Siti QC','Andi QC','Maya QC','Budi QC'];
        $decisions = ['Approve','Reject','Rework'];

        for ($d = 0; $d < 25; $d++) {
            $date = date('Y-m-d', strtotime("-{$d} days"));
            $count = rand(2, 5);
            for ($i = 0; $i < $count; $i++) {
                $p = $products[array_rand($products)];
                $dec = $decisions[array_rand($decisions)];
                $this->store->create([
                    'date' => $date,
                    'product_id' => $p['id'],
                    'product_name' => $p['name'],
                    'user_qc' => $users[array_rand($users)],
                    'dim_p' => rand(90, 310),
                    'dim_l' => rand(90, 310),
                    'dim_t' => rand(100, 400),
                    'dim_a' => rand(80, 200),
                    'dim_b' => rand(50, 150),
                    'dim_t2' => rand(5, 20),
                    'dim_s' => rand(3, 15),
                    'test_kebersihan' => ['OK','OK','OK','Not OK'][rand(0,3)],
                    'test_kualitas' => ['OK','OK','OK','Not OK'][rand(0,3)],
                    'test_layout' => ['OK','OK','Not OK'][rand(0,2)],
                    'test_drop' => ['Pass','Pass','Fail'][rand(0,2)],
                    'test_seep' => ['Pass','Pass','Fail'][rand(0,2)],
                    'test_ball' => ['Pass','Fail'][rand(0,1)],
                    'test_dimensi_visual' => ['OK','OK','Not OK'][rand(0,2)],
                    'kesimpulan' => $dec === 'Approve' ? 'Memenuhi standar' : ($dec === 'Reject' ? 'Tidak memenuhi standar' : 'Perlu perbaikan'),
                    'keputusan' => $dec,
                    'note' => $dec === 'Reject' ? 'Dimensi tidak sesuai spesifikasi' : ($dec === 'Rework' ? 'Perlu penyesuaian ulang' : '-'),
                ]);
            }
        }
    }

    public function index()
    {
        return view('monitoring-pengujian-kemasan');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['product_id'] ?? '', $q) !== false ||
                stripos($i['product_name'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_keputusan') && $request->filter_keputusan !== 'all')
            $data = array_filter($data, fn($i) => ($i['keputusan'] ?? '') === $request->filter_keputusan);

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('dimensi_fmt', fn($r) => $r['dim_p'].' × '.$r['dim_l'].' × '.$r['dim_t'].' mm')
            ->addColumn('keputusan_badge', function ($r) {
                return match($r['keputusan'] ?? '') {
                    'Approve' => '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Approve</span>',
                    'Reject' => '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Reject</span>',
                    'Rework' => '<span class="badge bg-warning text-dark"><i class="bi bi-arrow-repeat me-1"></i>Rework</span>',
                    default => '<span class="badge bg-secondary">'.$r['keputusan'].'</span>',
                };
            })
            ->addColumn('action', function ($r) {
                return '<div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-info" onclick="detailRecord(\''.$r['id'].'\')" title="Detail"><i class="bi bi-eye"></i></button>
                    <button class="btn btn-outline-primary" onclick="editRecord(\''.$r['id'].'\')" title="Edit"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-outline-danger" onclick="deleteRecord(\''.$r['id'].'\')" title="Hapus"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['date_fmt','dimensi_fmt','keputusan_badge','action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:200',
            'user_qc' => 'required|string|max:100',
        ]);

        $data = $request->only(['date','product_id','product_name','user_qc','dim_p','dim_l','dim_t','dim_a','dim_b','dim_t2','dim_s','test_kebersihan','test_kualitas','test_layout','test_drop','test_seep','test_ball','test_dimensi_visual','kesimpulan','keputusan','note']);
        $this->store->create($data);
        return response()->json(['success' => true, 'message' => 'Data pengujian berhasil disimpan.']);
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

        $request->validate(['product_name' => 'required', 'user_qc' => 'required']);
        $data = $request->only(['date','product_id','product_name','user_qc','dim_p','dim_l','dim_t','dim_a','dim_b','dim_t2','dim_s','test_kebersihan','test_kualitas','test_layout','test_drop','test_seep','test_ball','test_dimensi_visual','kesimpulan','keputusan','note']);
        $this->store->update($id, $data);
        return response()->json(['success' => true, 'message' => 'Data pengujian berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $this->store->delete($id);
        return response()->json(['success' => true, 'message' => 'Data pengujian berhasil dihapus.']);
    }
}
