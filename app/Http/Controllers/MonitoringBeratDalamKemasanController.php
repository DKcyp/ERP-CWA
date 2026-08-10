<?php

namespace App\Http\Controllers;

use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class MonitoringBeratDalamKemasanController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('monitoring-berat-dalam-kemasan');
        $this->initDummyData();
        View::share('activeMenu', 'monitoring-berat-dalam-kemasan');
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

        $containers = [
            'kaleng_01' => ['label' => 'Kaleng 0.1L', 'base' => 100],
            'kaleng_02' => ['label' => 'Kaleng 0.2L', 'base' => 200],
            'kaleng_04' => ['label' => 'Kaleng 0.4L', 'base' => 400],
            'kaleng_045' => ['label' => 'Kaleng 0.45L', 'base' => 450],
            'kaleng_09' => ['label' => 'Kaleng 0.9L', 'base' => 900],
            'kaleng' => ['label' => 'Kaleng', 'base' => 1000],
            'galon' => ['label' => 'Galon', 'base' => 5000],
            'pail' => ['label' => 'Pail', 'base' => 20000],
            'liter' => ['label' => 'Liter', 'base' => 1000],
            'kaleng_1l' => ['label' => 'Kaleng 1L', 'base' => 1000],
        ];

        for ($d = 0; $d < 30; $d++) {
            $date = date('Y-m-d', strtotime("-{$d} days"));
            $count = rand(3, 6);
            for ($i = 0; $i < $count; $i++) {
                $p = $products[array_rand($products)];
                $batch = 'BATCH-' . date('ymd', strtotime($date)) . '-' . strtoupper(substr(uniqid(), -4));
                $record = [
                    'production_id' => 'PRD-' . date('ymd', strtotime($date)) . '-' . rand(100, 999),
                    'date_test' => $date,
                    'product_id' => $p['id'],
                    'product_name' => $p['name'],
                    'batch_no' => $batch,
                    'user_id' => $users[array_rand($users)],
                ];
                foreach ($containers as $key => $c) {
                    $record[$key . '_awal'] = round($c['base'] + rand(-10, 15) + (rand(0, 100) / 100), 2);
                    $record[$key . '_tengah'] = round($c['base'] + rand(-8, 12) + (rand(0, 100) / 100), 2);
                    $record[$key . '_akhir'] = round($c['base'] + rand(-5, 10) + (rand(0, 100) / 100), 2);
                }
                $this->store->create($record);
            }
        }
    }

    public function index()
    {
        return view('monitoring-berat-dalam-kemasan');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['production_id'] ?? '', $q) !== false ||
                stripos($i['product_id'] ?? '', $q) !== false ||
                stripos($i['product_name'] ?? '', $q) !== false ||
                stripos($i['batch_no'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_date_from'))
            $data = array_filter($data, fn($i) => ($i['date_test'] ?? '') >= $request->filter_date_from);
        if ($request->filled('filter_date_to'))
            $data = array_filter($data, fn($i) => ($i['date_test'] ?? '') <= $request->filter_date_to);

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_test_fmt', fn($r) => $r['date_test'] ? \Carbon\Carbon::parse($r['date_test'])->format('d/m/Y') : '-')
            ->addColumn('kaleng_01_fmt', fn($r) => '<small class="text-muted">Awal:</small> '.$r['kaleng_01_awal'].'<br><small class="text-muted">Tengah:</small> '.$r['kaleng_01_tengah'].'<br><small class="text-muted">Akhir:</small> '.$r['kaleng_01_akhir'])
            ->addColumn('kaleng_02_fmt', fn($r) => '<small class="text-muted">Awal:</small> '.$r['kaleng_02_awal'].'<br><small class="text-muted">Tengah:</small> '.$r['kaleng_02_tengah'].'<br><small class="text-muted">Akhir:</small> '.$r['kaleng_02_akhir'])
            ->addColumn('kaleng_04_fmt', fn($r) => '<small class="text-muted">Awal:</small> '.$r['kaleng_04_awal'].'<br><small class="text-muted">Tengah:</small> '.$r['kaleng_04_tengah'].'<br><small class="text-muted">Akhir:</small> '.$r['kaleng_04_akhir'])
            ->addColumn('kaleng_045_fmt', fn($r) => '<small class="text-muted">Awal:</small> '.$r['kaleng_045_awal'].'<br><small class="text-muted">Tengah:</small> '.$r['kaleng_045_tengah'].'<br><small class="text-muted">Akhir:</small> '.$r['kaleng_045_akhir'])
            ->addColumn('kaleng_09_fmt', fn($r) => '<small class="text-muted">Awal:</small> '.$r['kaleng_09_awal'].'<br><small class="text-muted">Tengah:</small> '.$r['kaleng_09_tengah'].'<br><small class="text-muted">Akhir:</small> '.$r['kaleng_09_akhir'])
            ->addColumn('kaleng_fmt', fn($r) => '<small class="text-muted">Awal:</small> '.$r['kaleng_awal'].'<br><small class="text-muted">Tengah:</small> '.$r['kaleng_tengah'].'<br><small class="text-muted">Akhir:</small> '.$r['kaleng_akhir'])
            ->addColumn('galon_fmt', fn($r) => '<small class="text-muted">Awal:</small> '.$r['galon_awal'].'<br><small class="text-muted">Tengah:</small> '.$r['galon_tengah'].'<br><small class="text-muted">Akhir:</small> '.$r['galon_akhir'])
            ->addColumn('pail_fmt', fn($r) => '<small class="text-muted">Awal:</small> '.$r['pail_awal'].'<br><small class="text-muted">Tengah:</small> '.$r['pail_tengah'].'<br><small class="text-muted">Akhir:</small> '.$r['pail_akhir'])
            ->addColumn('liter_fmt', fn($r) => '<small class="text-muted">Awal:</small> '.$r['liter_awal'].'<br><small class="text-muted">Tengah:</small> '.$r['liter_tengah'].'<br><small class="text-muted">Akhir:</small> '.$r['liter_akhir'])
            ->addColumn('kaleng_1l_fmt', fn($r) => '<small class="text-muted">Awal:</small> '.$r['kaleng_1l_awal'].'<br><small class="text-muted">Tengah:</small> '.$r['kaleng_1l_tengah'].'<br><small class="text-muted">Akhir:</small> '.$r['kaleng_1l_akhir'])
            ->addColumn('action', function ($r) {
                return '<div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-info" onclick="detailRecord(\''.$r['id'].'\')" title="Detail"><i class="bi bi-eye"></i></button>
                    <button class="btn btn-outline-primary" onclick="editRecord(\''.$r['id'].'\')" title="Edit"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-outline-danger" onclick="deleteRecord(\''.$r['id'].'\')" title="Hapus"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['date_test_fmt','kaleng_01_fmt','kaleng_02_fmt','kaleng_04_fmt','kaleng_045_fmt','kaleng_09_fmt','kaleng_fmt','galon_fmt','pail_fmt','liter_fmt','kaleng_1l_fmt','action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:200',
            'batch_no' => 'required|string|max:100',
            'user_id' => 'required|string|max:100',
        ]);

        $fields = ['production_id','date_test','product_id','product_name','batch_no','user_id',
            'kaleng_01_awal','kaleng_01_tengah','kaleng_01_akhir',
            'kaleng_02_awal','kaleng_02_tengah','kaleng_02_akhir',
            'kaleng_04_awal','kaleng_04_tengah','kaleng_04_akhir',
            'kaleng_045_awal','kaleng_045_tengah','kaleng_045_akhir',
            'kaleng_09_awal','kaleng_09_tengah','kaleng_09_akhir',
            'kaleng_awal','kaleng_tengah','kaleng_akhir',
            'galon_awal','galon_tengah','galon_akhir',
            'pail_awal','pail_tengah','pail_akhir',
            'liter_awal','liter_tengah','liter_akhir',
            'kaleng_1l_awal','kaleng_1l_tengah','kaleng_1l_akhir',
        ];
        $data = $request->only($fields);
        foreach ($data as $k => $v) {
            if (in_array($k, ['production_id','date_test','product_id','product_name','batch_no','user_id'])) continue;
            $data[$k] = (float) ($v ?? 0);
        }
        $this->store->create($data);
        return response()->json(['success' => true, 'message' => 'Data monitoring berat berhasil disimpan.']);
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

        $request->validate(['product_name' => 'required', 'batch_no' => 'required', 'user_id' => 'required']);

        $fields = ['production_id','date_test','product_id','product_name','batch_no','user_id',
            'kaleng_01_awal','kaleng_01_tengah','kaleng_01_akhir',
            'kaleng_02_awal','kaleng_02_tengah','kaleng_02_akhir',
            'kaleng_04_awal','kaleng_04_tengah','kaleng_04_akhir',
            'kaleng_045_awal','kaleng_045_tengah','kaleng_045_akhir',
            'kaleng_09_awal','kaleng_09_tengah','kaleng_09_akhir',
            'kaleng_awal','kaleng_tengah','kaleng_akhir',
            'galon_awal','galon_tengah','galon_akhir',
            'pail_awal','pail_tengah','pail_akhir',
            'liter_awal','liter_tengah','liter_akhir',
            'kaleng_1l_awal','kaleng_1l_tengah','kaleng_1l_akhir',
        ];
        $data = $request->only($fields);
        foreach ($data as $k => $v) {
            if (in_array($k, ['production_id','date_test','product_id','product_name','batch_no','user_id'])) continue;
            $data[$k] = (float) ($v ?? 0);
        }
        $this->store->update($id, $data);
        return response()->json(['success' => true, 'message' => 'Data monitoring berat berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $this->store->delete($id);
        return response()->json(['success' => true, 'message' => 'Data monitoring berat berhasil dihapus.']);
    }
}
