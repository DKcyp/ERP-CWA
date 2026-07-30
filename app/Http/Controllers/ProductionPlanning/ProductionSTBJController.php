<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ProductionSTBJController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('stbj');
        $this->initDummyData();
        View::share('activeMenu', 'stbj');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $lines = ['LINE-A1','LINE-A2','LINE-B1','LINE-B2','LINE-C1'];
        $warehouses = ['Gudang Jadi Bandung','Gudang Jadi Jakarta','Gudang Jadi Surabaya'];
        $receivers = ['Ahmad Hidayat','Dewi Lestari','Rudi Hermawan','Siti Nurhaliza','Bambang Sutrisno'];
        $statuses = ['Draft','Issued','Received','Verified'];
        $products = ['Wall Paint White 20L','Wall Paint Cream 10L','Primer Grey 5L','Top Coat Clear 15L','Cat Ekonomis 5L'];

        $data = [];
        for ($d = 0; $d < 5; $d++) {
            $date = date('Y-m-d', strtotime("2026-07-26 +{$d} days"));
            for ($r = 0; $r < rand(2, 4); $r++) {
                $pcs = rand(50, 300);
                $kg = round($pcs * rand(8, 15) / 10, 1);
                $prodId = 'PRD-LST-'.str_pad(rand(1, 17), 4, '0', STR_PAD_LEFT);
                $data[] = [
                    'stbj_no' => 'STBJ-'.date('Ymd', strtotime($date)).'-'.str_pad($d * 4 + $r + 1, 3, '0', STR_PAD_LEFT),
                    'date' => $date,
                    'production_id' => $prodId,
                    'batch_no' => 'BN-'.str_pad(rand(400, 500), 4, '0', STR_PAD_LEFT),
                    'product_name' => $products[array_rand($products)],
                    'from_line' => $lines[array_rand($lines)],
                    'to_warehouse_id' => $warehouses[array_rand($warehouses)],
                    'total_qty_pcs' => $pcs,
                    'total_weight_kg' => $kg,
                    'received_by' => $receivers[array_rand($receivers)],
                    'status' => $statuses[array_rand($statuses)],
                    'notes' => '',
                ];
            }
        }
        foreach ($data as $item) { $this->store->create($item); }
    }

    public function index()
    {
        return view('production-planning.stbj.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['stbj_no'] ?? '', $q) !== false ||
                stripos($i['production_id'] ?? '', $q) !== false ||
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
            ->addColumn('pcs_fmt', fn($r) => number_format($r['total_qty_pcs'], 0, ',', '.'))
            ->addColumn('kg_fmt', fn($r) => number_format($r['total_weight_kg'], 1, ',', '.'))
            ->addColumn('status_badge', function ($r) {
                return match($r['status'] ?? '') {
                    'Received' => '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Received</span>',
                    'Verified' => '<span class="badge bg-info"><i class="bi bi-patch-check me-1"></i>Verified</span>',
                    'Issued' => '<span class="badge bg-warning text-dark"><i class="bi bi-send me-1"></i>Issued</span>',
                    default => '<span class="badge bg-secondary"><i class="bi bi-pencil me-1"></i>Draft</span>',
                };
            })
            ->addColumn('action', function ($r) {
                $id = $r['id'];
                $st = $r['status'];
                $btns = '<div class="btn-group btn-group-sm">';
                $btns .= '<button class="btn btn-outline-primary" onclick="editRecord(\''.$id.'\')" title="Edit"><i class="bi bi-pencil"></i></button>';
                if ($st === 'Draft') $btns .= '<button class="btn btn-outline-success btn-sm" onclick="issueSTBJ(\''.$id.'\')" title="Issue"><i class="bi bi-send"></i></button>';
                if ($st === 'Issued') $btns .= '<button class="btn btn-outline-info btn-sm" onclick="verifySTBJ(\''.$id.'\')" title="Verify"><i class="bi bi-patch-check"></i></button>';
                $btns .= '<button class="btn btn-outline-secondary btn-sm" onclick="printSTBJ(\''.$id.'\')" title="Cetak"><i class="bi bi-printer"></i></button>';
                $btns .= '<button class="btn btn-outline-danger btn-sm" onclick="deleteRecord(\''.$id.'\')" title="Hapus"><i class="bi bi-trash"></i></button>';
                $btns .= '</div>';
                return $btns;
            })
            ->rawColumns(['date_fmt','pcs_fmt','kg_fmt','status_badge','action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date', 'production_id' => 'required', 'batch_no' => 'required',
            'from_line' => 'required', 'to_warehouse_id' => 'required',
            'total_qty_pcs' => 'required|integer|min:1', 'total_weight_kg' => 'required|numeric|min:0.1',
        ]);

        $data = $request->only(['stbj_no','date','production_id','batch_no','product_name','from_line','to_warehouse_id','total_qty_pcs','total_weight_kg','received_by','status','notes']);
        if (empty($data['stbj_no'])) {
            $data['stbj_no'] = 'STBJ-'.date('Ymd', strtotime($request->date)).'-'.str_pad(count($this->store->all()) + 1, 3, '0', STR_PAD_LEFT);
        }
        $data['status'] = $data['status'] ?? 'Draft';

        $this->store->create($data);

        return response()->json(['success' => true, 'message' => 'STBJ berhasil disimpan.']);
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
            'date' => 'required|date', 'production_id' => 'required', 'batch_no' => 'required',
            'from_line' => 'required', 'to_warehouse_id' => 'required',
            'total_qty_pcs' => 'required|integer|min:1', 'total_weight_kg' => 'required|numeric|min:0.1',
        ]);

        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);

        $data = $request->only(['date','production_id','batch_no','product_name','from_line','to_warehouse_id','total_qty_pcs','total_weight_kg','received_by','status','notes']);
        $this->store->update($id, $data);

        return response()->json(['success' => true, 'message' => 'STBJ berhasil diperbarui.']);
    }

    public function issue($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $this->store->update($id, ['status' => 'Issued']);
        return response()->json(['success' => true, 'message' => 'STBJ berhasil di-issue.']);
    }

    public function verify($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $this->store->update($id, ['status' => 'Verified']);
        return response()->json(['success' => true, 'message' => 'STBJ berhasil diverifikasi.']);
    }

    public function destroy($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $this->store->delete($id);
        return response()->json(['success' => true, 'message' => 'STBJ berhasil dihapus.']);
    }
}