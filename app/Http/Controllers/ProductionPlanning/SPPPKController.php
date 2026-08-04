<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SPPPKController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('production-spppk');
        $this->initDummyData();
        View::share('activeMenu', 'production-process');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $products = ['Wall Paint White 20L','Wall Paint Cream 10L','Primer Grey 5L','Top Coat Clear 15L','Cat Ekonomis 5L'];
        $lines = ['PACK-01','PACK-02','PACK-03','PACK-04'];
        $packageTypes = [
            ['type' => 'Kaleng 0.9L', 'tare' => 0.15],
            ['type' => 'Kaleng 0.1L', 'tare' => 0.05],
            ['type' => 'Galon 5L', 'tare' => 0.30],
            ['type' => 'Galon 10L', 'tare' => 0.45],
            ['type' => 'Pail 15L', 'tare' => 0.60],
            ['type' => 'Pail 20L', 'tare' => 0.75],
        ];
        $operators = ['Ahmad Hidayat','Dewi Lestari','Rudi Hermawan','Siti Nurhaliza','Bambang Sutrisno','Lina Maulida'];
        $statuses = ['Completed','In Progress','Draft'];

        for ($d = 0; $d < 21; $d++) {
            $date = date('Y-m-d', strtotime("2026-07-10 +{$d} days"));
            $count = rand(2, 4);
            for ($i = 0; $i < $count; $i++) {
                $pt = $packageTypes[array_rand($packageTypes)];
                $batch = 'BN-'.str_pad(rand(401, 500), 4, '0', STR_PAD_LEFT);
                $targetPcs = rand(50, 300);
                $targetKg = $targetPcs * rand(1, 10) / 10;
                $actualPcs = $targetPcs + rand(-10, 15);
                $actualKg = round($actualPcs * ($targetKg / max(1, $targetPcs)), 2);
                $rejectPcs = rand(0, 5);

                $this->store->create([
                    'production_id' => 'PRD-LST-'.str_pad(rand(1, 17), 4, '0', STR_PAD_LEFT),
                    'spppk_no' => 'SPPPK-'.date('ymd', strtotime($date)).'-'.str_pad($d * 4 + $i + 1, 3, '0', STR_PAD_LEFT),
                    'date' => $date,
                    'created_by' => $operators[array_rand($operators)],
                    'batch_no' => $batch,
                    'product_name' => $products[array_rand($products)],
                    'packaging_line_id' => $lines[array_rand($lines)],
                    'package_type' => $pt['type'],
                    'target_packing_qty_pcs' => $targetPcs,
                    'target_weight_kg' => $targetKg,
                    'tare_weight_check' => $pt['tare'],
                    'actual_packed_pcs' => $actualPcs,
                    'actual_packed_kg' => $actualKg,
                    'reject_packaging_pcs' => $rejectPcs,
                    'operator_packing' => $operators[array_rand($operators)],
                    'notes' => 'Pengisian '.$pt['type'].' batch '.$batch,
                    'status' => $statuses[array_rand($statuses)],
                ]);
            }
        }
    }

    public function index()
    {
        return view('production-planning.production-process-spppk');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['spppk_no'] ?? '', $q) !== false ||
                stripos($i['batch_no'] ?? '', $q) !== false ||
                stripos($i['product_name'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_date_from')) $data = array_filter($data, fn($i) => ($i['date'] ?? '') >= $request->filter_date_from);
        if ($request->filled('filter_date_to')) $data = array_filter($data, fn($i) => ($i['date'] ?? '') <= $request->filter_date_to);
        if ($request->filled('filter_status') && $request->filter_status !== 'all') $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $request->filter_status);

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('target_pcs_fmt', fn($r) => number_format($r['target_packing_qty_pcs'] ?? 0, 0, ',', '.'))
            ->addColumn('target_kg_fmt', fn($r) => number_format($r['target_weight_kg'] ?? 0, 2, ',', '.'))
            ->addColumn('actual_pcs_fmt', fn($r) => number_format($r['actual_packed_pcs'] ?? 0, 0, ',', '.'))
            ->addColumn('actual_kg_fmt', fn($r) => number_format($r['actual_packed_kg'] ?? 0, 2, ',', '.'))
            ->addColumn('status_badge', function ($r) {
                return match($r['status'] ?? '') {
                    'Completed' => '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Completed</span>',
                    'In Progress' => '<span class="badge bg-info"><i class="bi bi-hourglass-split me-1"></i>In Progress</span>',
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
            ->rawColumns(['date_fmt','target_pcs_fmt','target_kg_fmt','actual_pcs_fmt','actual_kg_fmt','status_badge','action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date', 'product_name' => 'required', 'packaging_line_id' => 'required',
            'package_type' => 'required',
        ]);

        $data = $request->only(['production_id','spppk_no','date','created_by','batch_no','product_name','packaging_line_id','package_type','target_packing_qty_pcs','target_weight_kg','tare_weight_check','actual_packed_pcs','actual_packed_kg','reject_packaging_pcs','operator_packing','notes','status']);
        if (empty($data['spppk_no'])) $data['spppk_no'] = 'SPPPK-'.date('ymd', strtotime($request->date)).'-'.str_pad(count($this->store->all()) + 1, 3, '0', STR_PAD_LEFT);
        $data['status'] = $data['status'] ?? 'Draft';

        $this->store->create($data);
        return response()->json(['success' => true, 'message' => 'SPPPK berhasil disimpan.']);
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
            'date' => 'required|date', 'product_name' => 'required', 'packaging_line_id' => 'required',
            'package_type' => 'required',
        ]);

        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);

        $data = $request->only(['production_id','spppk_no','date','created_by','batch_no','product_name','packaging_line_id','package_type','target_packing_qty_pcs','target_weight_kg','tare_weight_check','actual_packed_pcs','actual_packed_kg','reject_packaging_pcs','operator_packing','notes','status']);
        $this->store->update($id, $data);

        return response()->json(['success' => true, 'message' => 'SPPPK berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $this->store->delete($id);
        return response()->json(['success' => true, 'message' => 'SPPPK berhasil dihapus.']);
    }
}
