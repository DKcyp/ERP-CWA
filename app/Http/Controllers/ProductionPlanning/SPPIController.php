<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SPPIController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('production-sppi');
        $this->initDummyData();
        View::share('activeMenu', 'production-process');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $products = [
            'Wall Paint White 20L','Wall Paint Cream 10L','Primer Grey 5L','Top Coat Clear 15L','Cat Ekonomis 5L',
        ];

        $machines = ['Mixer A-1','Mixer A-2','Mixer B-1','Mixer B-2','Mixer C-1'];
        $users = ['Ahmad Hidayat','Dewi Lestari','Rudi Hermawan','Siti Nurhaliza','Bambang Sutrisno','Lina Maulida'];
        $statuses = ['Completed','Pending QC','Draft'];

        $materials = [
            ['id' => 'MAT-AD-001', 'name' => 'Anti Jamur AG-200', 'uom' => 'Kg'],
            ['id' => 'MAT-AD-002', 'name' => 'Biosida BACT-50', 'uom' => 'L'],
            ['id' => 'MAT-AD-003', 'name' => 'Insektisida INSECT-10', 'uom' => 'Kg'],
            ['id' => 'MAT-AD-004', 'name' => 'Anti Busuk AB-100', 'uom' => 'Kg'],
            ['id' => 'MAT-AD-005', 'name' => 'Pengawet Khusus PK-30', 'uom' => 'L'],
            ['id' => 'MAT-AD-006', 'name' => 'Defoamer Industrial DI-20', 'uom' => 'Kg'],
            ['id' => 'MAT-AD-007', 'name' => 'Retarder Special RS-15', 'uom' => 'L'],
        ];

        for ($d = 0; $d < 21; $d++) {
            $date = date('Y-m-d', strtotime("2026-07-10 +{$d} days"));
            $count = rand(2, 5);
            for ($i = 0; $i < $count; $i++) {
                $m = $materials[array_rand($materials)];
                $batch = 'BN-'.str_pad(rand(401, 500), 4, '0', STR_PAD_LEFT);
                $target = round(rand(5, 50) / 10, 2);
                $actual = round($target + rand(-2, 5) / 10, 2);

                $this->store->create([
                    'production_id' => 'PRD-LST-'.str_pad(rand(1, 17), 4, '0', STR_PAD_LEFT),
                    'sppi_no' => 'SPPI-'.date('ymd', strtotime($date)).'-'.str_pad($d * 5 + $i + 1, 3, '0', STR_PAD_LEFT),
                    'date' => $date,
                    'created_by' => $users[array_rand($users)],
                    'batch_no' => $batch,
                    'product_name' => $products[array_rand($products)],
                    'machine' => $machines[array_rand($machines)],
                    'material_id' => $m['id'],
                    'material_name' => $m['name'],
                    'target_dose_qty' => $target,
                    'actual_dose_qty' => $actual,
                    'uom' => $m['uom'],
                    'mixing_time' => rand(5, 30).' menit',
                    'operator' => $users[array_rand($users)],
                    'notes' => 'Penambahan '.$m['name'].' ke batch '.$batch,
                    'status' => $statuses[array_rand($statuses)],
                ]);
            }
        }
    }

    public function index()
    {
        return view('production-planning.production-process-sppi');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['sppi_no'] ?? '', $q) !== false ||
                stripos($i['batch_no'] ?? '', $q) !== false ||
                stripos($i['product_name'] ?? '', $q) !== false ||
                stripos($i['material_name'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_date_from')) $data = array_filter($data, fn($i) => ($i['date'] ?? '') >= $request->filter_date_from);
        if ($request->filled('filter_date_to')) $data = array_filter($data, fn($i) => ($i['date'] ?? '') <= $request->filter_date_to);
        if ($request->filled('filter_status') && $request->filter_status !== 'all') $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $request->filter_status);

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('target_fmt', fn($r) => number_format($r['target_dose_qty'] ?? 0, 2, ',', '.'))
            ->addColumn('actual_fmt', fn($r) => number_format($r['actual_dose_qty'] ?? 0, 2, ',', '.'))
            ->addColumn('status_badge', function ($r) {
                return match($r['status'] ?? '') {
                    'Completed' => '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Completed</span>',
                    'Pending QC' => '<span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Pending QC</span>',
                    'Draft' => '<span class="badge bg-secondary"><i class="bi bi-pencil me-1"></i>Draft</span>',
                    default => '<span class="badge bg-secondary">'.$r['status'].'</span>',
                };
            })
            ->addColumn('action', function ($r) {
                $id = $r['id'];
                $btns = '<div class="btn-group btn-group-sm">';
                $btns .= '<button class="btn btn-outline-info" onclick="detailRecord(\''.$id.'\')" title="Detail"><i class="bi bi-eye"></i></button>';
                $btns .= '<button class="btn btn-outline-primary" onclick="editRecord(\''.$id.'\')" title="Edit"><i class="bi bi-pencil"></i></button>';
                $btns .= '<button class="btn btn-outline-danger" onclick="deleteRecord(\''.$id.'\')" title="Hapus"><i class="bi bi-trash"></i></button>';
                $btns .= '</div>';
                return $btns;
            })
            ->rawColumns(['date_fmt','target_fmt','actual_fmt','status_badge','action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date', 'product_name' => 'required', 'machine' => 'required',
            'material_name' => 'required', 'target_dose_qty' => 'required|numeric',
        ]);

        $data = $request->only(['production_id','sppi_no','date','created_by','batch_no','product_name','machine','material_id','material_name','target_dose_qty','actual_dose_qty','uom','mixing_time','operator','notes','status']);
        if (empty($data['sppi_no'])) $data['sppi_no'] = 'SPPI-'.date('ymd', strtotime($request->date)).'-'.str_pad(count($this->store->all()) + 1, 3, '0', STR_PAD_LEFT);
        $data['status'] = $data['status'] ?? 'Draft';

        $this->store->create($data);
        return response()->json(['success' => true, 'message' => 'SPPI berhasil disimpan.']);
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
            'material_name' => 'required', 'target_dose_qty' => 'required|numeric',
        ]);

        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);

        $data = $request->only(['production_id','sppi_no','date','created_by','batch_no','product_name','machine','material_id','material_name','target_dose_qty','actual_dose_qty','uom','mixing_time','operator','notes','status']);
        $this->store->update($id, $data);

        return response()->json(['success' => true, 'message' => 'SPPI berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $this->store->delete($id);
        return response()->json(['success' => true, 'message' => 'SPPI berhasil dihapus.']);
    }
}
