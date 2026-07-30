<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class MonitoringMesinGrindingListController extends Controller
{
    protected DummyStore $headerStore;
    protected DummyStore $detailStore;

    public function __construct()
    {
        $this->headerStore = new DummyStore('monitoring-mesin-grinding-list');
        $this->detailStore = new DummyStore('monitoring-mesin-grinding-list-detail');
        $this->initDummyData();
        View::share('activeMenu', 'monitoring-mesin-grinding-list');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->headerStore->all())) return;

        $products = ['Wall Paint White 20L','Wall Paint Cream 10L','Primer Grey 5L','Top Coat Clear 15L','Cat Ekonomis 5L','Pigment Red Oxide','Pigment Yellow Ochre'];
        $machines = ['GR-01','GR-02','GR-03'];
        $operators = ['Budi Santoso','Andi Kurniawan','Citra Dewi','Dedi Kuswanto','Eka Putri','Fajar Nugroho'];
        $types = ['Water Based','Solvent Based'];
        $shifts = ['Shift 1','Shift 2','Shift 3'];
        $statuses = ['Draft','Submitted','Approved','Rejected'];

        $data = [
            ['date'=>'2026-07-28','user_id'=>'gr-001','prod_date'=>'2026-07-28','shift'=>'Shift 1','machine'=>'GR-01','type'=>'Water Based','status'=>'Approved','notes'=>''],
            ['date'=>'2026-07-28','user_id'=>'gr-002','prod_date'=>'2026-07-28','shift'=>'Shift 2','machine'=>'GR-02','type'=>'Solvent Based','status'=>'Submitted','notes'=>''],
            ['date'=>'2026-07-29','user_id'=>'gr-001','prod_date'=>'2026-07-29','shift'=>'Shift 1','machine'=>'GR-03','type'=>'Water Based','status'=>'Approved','notes'=>''],
            ['date'=>'2026-07-29','user_id'=>'gr-003','prod_date'=>'2026-07-29','shift'=>'Shift 2','machine'=>'GR-01','type'=>'Solvent Based','status'=>'Draft','notes'=>''],
            ['date'=>'2026-07-30','user_id'=>'gr-002','prod_date'=>'2026-07-30','shift'=>'Shift 1','machine'=>'GR-02','type'=>'Water Based','status'=>'Approved','notes'=>''],
            ['date'=>'2026-07-30','user_id'=>'gr-001','prod_date'=>'2026-07-30','shift'=>'Shift 3','machine'=>'GR-01','type'=>'Solvent Based','status'=>'Rejected','notes'=>'Micron tidak sesuai'],
            ['date'=>'2026-07-31','user_id'=>'gr-003','prod_date'=>'2026-07-31','shift'=>'Shift 1','machine'=>'GR-03','type'=>'Water Based','status'=>'Submitted','notes'=>''],
        ];

        $id = 1;
        foreach ($data as $item) {
            $docId = 'MGR-'.date('Ymd', strtotime($item['date'])).'-'.str_pad($id, 3, '0', STR_PAD_LEFT);
            $detailCount = rand(2, 4);
            $totalTon = 0;
            $details = [];
            for ($d = 0; $d < $detailCount; $d++) {
                $tonase = round(rand(500, 2000) / 100, 2);
                $totalTon += $tonase;
                $startH = 6 + $d * 4;
                $ke = $d + 1;
                $jamObservasi = round(rand(10, 45) / 10, 1);
                $details[] = [
                    'doc_id' => $docId,
                    'date' => $item['date'],
                    'product_name' => $products[array_rand($products)],
                    'batch_no' => 'GR-ADN-'.str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT),
                    'tonase' => $tonase,
                    'no_mesin' => $item['machine'],
                    'mulai' => sprintf('%02d:%02d', $startH, rand(0, 29)),
                    'finish' => sprintf('%02d:%02d', $startH + rand(2, 4), rand(0, 59)),
                    'ke' => $ke,
                    'jam' => $jamObservasi,
                    'speed_gear_pump' => rand(800, 1500),
                    'speed_blade' => rand(1000, 2500),
                    'hasil_micron' => round(rand(5, 50) / 10, 1),
                    'operator' => $operators[array_rand($operators)],
                    'notes' => '',
                ];
            }
            $item['doc_id'] = $docId;
            $item['total_product_count'] = $detailCount;
            $item['total_tonase'] = $totalTon;
            $this->headerStore->create($item);
            foreach ($details as $d) { $this->detailStore->create($d); }
            $id++;
        }
    }

    public function index()
    {
        return view('production-planning.monitoring-mesin-grinding-list.index');
    }

    public function table(Request $request)
    {
        $data = $this->headerStore->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['doc_id'] ?? '', $q) !== false ||
                stripos($i['machine'] ?? '', $q) !== false ||
                stripos($i['user_id'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_date_from')) $data = array_filter($data, fn($i) => ($i['date'] ?? '') >= $request->filter_date_from);
        if ($request->filled('filter_date_to')) $data = array_filter($data, fn($i) => ($i['date'] ?? '') <= $request->filter_date_to);
        if ($request->filled('filter_shift') && $request->filter_shift !== 'all') $data = array_filter($data, fn($i) => ($i['shift'] ?? '') === $request->filter_shift);
        if ($request->filled('filter_machine') && $request->filter_machine !== 'all') $data = array_filter($data, fn($i) => ($i['machine'] ?? '') === $request->filter_machine);
        if ($request->filled('filter_type') && $request->filter_type !== 'all') $data = array_filter($data, fn($i) => ($i['type'] ?? '') === $request->filter_type);

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('prod_date_fmt', fn($r) => \Carbon\Carbon::parse($r['prod_date'])->format('d/m/Y'))
            ->addColumn('tonase_fmt', fn($r) => number_format($r['total_tonase'], 2, ',', '.'))
            ->addColumn('status_badge', function ($r) {
                return match($r['status'] ?? '') {
                    'Approved' => '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Approved</span>',
                    'Submitted' => '<span class="badge bg-info"><i class="bi bi-send me-1"></i>Submitted</span>',
                    'Rejected' => '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Rejected</span>',
                    default => '<span class="badge bg-secondary"><i class="bi bi-pencil me-1"></i>Draft</span>',
                };
            })
            ->addColumn('action', function ($r) {
                $id = $r['id'];
                return '<div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" onclick="editRecord(\''.$id.'\')" title="Edit"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-outline-danger" onclick="deleteRecord(\''.$id.'\')" title="Hapus"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['date_fmt','prod_date_fmt','tonase_fmt','status_badge','action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date', 'prod_date' => 'required|date', 'shift' => 'required',
            'machine' => 'required', 'type' => 'required',
            'items' => 'required|array|min:1',
        ]);

        $items = $request->items;
        $totalTon = array_sum(array_column($items, 'tonase'));

        $headerData = $request->only(['date','user_id','prod_date','shift','machine','type','status','notes']);
        $headerData['total_product_count'] = count($items);
        $headerData['total_tonase'] = $totalTon;
        $headerData['status'] = $headerData['status'] ?? 'Draft';

        $saved = $this->headerStore->create($headerData);

        foreach ($items as $item) {
            $item['doc_id'] = $saved['doc_id'] ?? '';
            $this->detailStore->create($item);
        }

        return response()->json(['success' => true, 'message' => 'Monitoring Grinding berhasil disimpan.']);
    }

    public function show($id)
    {
        $header = $this->headerStore->find($id);
        if (!$header) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $details = array_values(array_filter($this->detailStore->all(), fn($d) => ($d['doc_id'] ?? '') === ($header['doc_id'] ?? '')));
        $header['items'] = $details;
        return response()->json($header);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date', 'prod_date' => 'required|date', 'shift' => 'required',
            'machine' => 'required', 'type' => 'required', 'items' => 'required|array|min:1',
        ]);

        $header = $this->headerStore->find($id);
        if (!$header) return response()->json(['error' => 'Data tidak ditemukan'], 404);

        $items = $request->items;
        $headerData = $request->only(['date','user_id','prod_date','shift','machine','type','status','notes']);
        $headerData['total_product_count'] = count($items);
        $headerData['total_tonase'] = array_sum(array_column($items, 'tonase'));

        $this->headerStore->update($id, $headerData);

        $docId = $header['doc_id'] ?? '';
        $existing = array_values(array_filter($this->detailStore->all(), fn($d) => ($d['doc_id'] ?? '') !== $docId));
        $this->detailStore->overwriteAll($existing);
        foreach ($items as $item) { $item['doc_id'] = $docId; $this->detailStore->create($item); }

        return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $header = $this->headerStore->find($id);
        if (!$header) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $docId = $header['doc_id'] ?? '';
        $remaining = array_values(array_filter($this->detailStore->all(), fn($d) => ($d['doc_id'] ?? '') !== $docId));
        $this->detailStore->overwriteAll($remaining);
        $this->headerStore->delete($id);
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
    }
}