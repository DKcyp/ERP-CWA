<?php

namespace App\Http\Controllers;

use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class MarketingVisitController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('marketing-visit');
        $this->initDummyData();
        View::share('activeMenu', 'marketing-visit');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $hari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        $types = ['Canvas','Routine','Prospeksi'];
        $people = [
            ['id'=>'NC-00001','name'=>'PT Maju Jaya 1'],['id'=>'NC-00002','name'=>'CV Berkah 2'],
            ['id'=>'NC-00003','name'=>'Toko Sinar 3'],['id'=>'NC-00004','name'=>'UD Makmur 4'],
            ['id'=>'NC-00005','name'=>'PT Sentosa 5'],['id'=>'NC-00006','name'=>'CV Pelangi 6'],
            ['id'=>'NC-00007','name'=>'Toko Abadi 7'],['id'=>'NC-00008','name'=>'UD Sejahtera 8'],
            ['id'=>'NC-00009','name'=>'PT Bintang 9'],['id'=>'NC-00010','name'=>'CV Cahaya 10'],
            ['id'=>'CUST-001','name'=>'PT Maju Jaya Abadi'],['id'=>'CUST-002','name'=>'CV Berkah Mulia'],
            ['id'=>'CUST-003','name'=>'PT Sinar Terang Perkasa'],['id'=>'CUST-004','name'=>'CV Pelangi Cat Indonesia'],
            ['id'=>'CUST-005','name'=>'PT Sentosa Paint'],
        ];

        for ($d = 0; $d < 30; $d++) {
            $date = date('Y-m-d', strtotime("-{$d} days"));
            $dayName = $hari[(int)date('w', strtotime($date)) - 1] ?? 'Senin';
            $visitCount = rand(3, 8);
            for ($v = 0; $v < $visitCount; $v++) {
                $p = $people[array_rand($people)];
                $this->store->create([
                    'date' => $date,
                    'hari' => $dayName,
                    'id_ref' => $p['id'],
                    'name' => $p['name'],
                    'tipe' => $types[array_rand($types)],
                    'noo' => rand(0, 10) > 7 ? 'Y' : 'N',
                ]);
            }
        }
    }

    public function index()
    {
        return view('marketing-visit');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['id_ref'] ?? '', $q) !== false ||
                stripos($i['name'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_date_from')) $data = array_filter($data, fn($i) => ($i['date'] ?? '') >= $request->filter_date_from);
        if ($request->filled('filter_date_to')) $data = array_filter($data, fn($i) => ($i['date'] ?? '') <= $request->filter_date_to);
        if ($request->filled('filter_noo') && $request->filter_noo !== 'all')
            $data = array_filter($data, fn($i) => ($i['noo'] ?? 'N') === $request->filter_noo);

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('noo_badge', function ($r) {
                return ($r['noo'] ?? 'N') === 'Y'
                    ? '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Ya</span>'
                    : '<span class="badge bg-secondary">Tidak</span>';
            })
            ->addColumn('tipe_badge', function ($r) {
                return match($r['tipe'] ?? '') {
                    'Canvas' => '<span class="badge bg-primary"><i class="bi bi-pencil-square me-1"></i>Canvas</span>',
                    'Routine' => '<span class="badge bg-info"><i class="bi bi-arrow-repeat me-1"></i>Routine</span>',
                    'Prospeksi' => '<span class="badge bg-warning text-dark"><i class="bi bi-search me-1"></i>Prospeksi</span>',
                    default => '<span class="badge bg-secondary">'.$r['tipe'].'</span>',
                };
            })
            ->addColumn('action', function ($r) {
                return '<div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" onclick="editRecord(\''.$r['id'].'\')" title="Edit"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-outline-danger" onclick="deleteRecord(\''.$r['id'].'\')" title="Hapus"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['date_fmt','noo_badge','tipe_badge','action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'name' => 'required|string|max:200',
            'tipe' => 'required|string|max:50',
        ]);

        $data = $request->only(['date','hari','id_ref','name','tipe','noo']);
        if (empty($data['hari'])) {
            $data['hari'] = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][(int)date('w', strtotime($data['date']))];
        }
        $data['noo'] = $data['noo'] ?? 'N';

        $this->store->create($data);
        return response()->json(['success' => true, 'message' => 'Marketing visit berhasil disimpan.']);
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

        $request->validate(['date' => 'required|date', 'name' => 'required', 'tipe' => 'required']);
        $data = $request->only(['date','hari','id_ref','name','tipe','noo']);
        if (empty($data['hari'])) {
            $data['hari'] = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][(int)date('w', strtotime($data['date']))];
        }
        $this->store->update($id, $data);

        return response()->json(['success' => true, 'message' => 'Marketing visit berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $this->store->delete($id);
        return response()->json(['success' => true, 'message' => 'Marketing visit berhasil dihapus.']);
    }
}
