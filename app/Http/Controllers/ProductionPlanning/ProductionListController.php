<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ProductionListController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('production-list');
        $this->initDummyData();
        View::share('activeMenu', 'production-list');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $data = [
            [
                'production_id'  => 'PRD-LST-0001','template_name' => 'Wall Paint Standard','formulasi' => 'F-001','basis' => 'Standard',
                'qty_jadwal'     => 200,'fk' => 'FK-001','jadwal' => '2026-07-28','produksi' => '2026-07-28','recanning' => '',
                'batch_no'       => 'BN-0421','no_spkp' => 'SPKP-2026-0018','date' => '2026-07-28','no_box_arsip' => 'BOX-A001',
                'tipe_product'   => 'Water Based','product_group' => 'Wall Paint','reference' => 'SPK-2026-0001','machine' => 'LINE-A1',
                'status'         => 'COMPLETED','user_id' => 'USR-001','notes' => 'Batch produksi rutin',
                'stock_release'  => 210,'stock_receive' => 195,'qc' => 'PASS','adjustment' => 5,'total_material' => 210,'total_realisasi' => 195,'selisih' => 15,
                'adj_batch'      => 0,'kesimpulan' => 'Produksi sesuai target','keputusan' => 'LULUS',
            ],
            [
                'production_id'  => 'PRD-LST-0002','template_name' => 'Wall Paint Standard','formulasi' => 'F-001','basis' => 'Standard',
                'qty_jadwal'     => 150,'fk' => 'FK-002','jadwal' => '2026-07-28','produksi' => '2026-07-28','recanning' => '',
                'batch_no'       => 'BN-0422','no_spkp' => 'SPKP-2026-0019','date' => '2026-07-28','no_box_arsip' => 'BOX-A002',
                'tipe_product'   => 'Water Based','product_group' => 'Wall Paint','reference' => 'SPK-2026-0001','machine' => 'LINE-A1',
                'status'         => 'COMPLETED','user_id' => 'USR-001','notes' => '',
                'stock_release'  => 160,'stock_receive' => 152,'qc' => 'PASS','adjustment' => 3,'total_material' => 160,'total_realisasi' => 152,'selisih' => 8,
                'adj_batch'      => 0,'kesimpulan' => 'Selisih wajar','keputusan' => 'LULUS',
            ],
            [
                'production_id'  => 'PRD-LST-0003','template_name' => 'Primer Grey','formulasi' => 'F-006','basis' => 'Standard',
                'qty_jadwal'     => 300,'fk' => 'FK-003','jadwal' => '2026-07-29','produksi' => '2026-07-29','recanning' => '',
                'batch_no'       => 'BN-0423','no_spkp' => 'SPKP-2026-0020','date' => '2026-07-29','no_box_arsip' => 'BOX-B001',
                'tipe_product'   => 'Water Based','product_group' => 'Primer','reference' => 'SPK-2026-0003','machine' => 'LINE-A2',
                'status'         => 'IN_PROGRESS','user_id' => 'USR-002','notes' => 'Sedang proses grinding',
                'stock_release'  => 310,'stock_receive' => 0,'qc' => '-','adjustment' => 0,'total_material' => 310,'total_realisasi' => 0,'selisih' => 0,
                'adj_batch'      => 0,'kesimpulan' => '','keputusan' => '',
            ],
            [
                'production_id'  => 'PRD-LST-0004','template_name' => 'Top Coat Clear','formulasi' => 'F-007','basis' => 'Premium',
                'qty_jadwal'     => 180,'fk' => 'FK-004','jadwal' => '2026-07-29','produksi' => '','recanning' => '',
                'batch_no'       => '','no_spkp' => 'SPKP-2026-0021','date' => '2026-07-29','no_box_arsip' => '',
                'tipe_product'   => 'Solvent Based','product_group' => 'Top Coat','reference' => 'SPK-2026-0004','machine' => 'LINE-B1',
                'status'         => 'PLANNED','user_id' => 'USR-003','notes' => 'Menunggu material',
                'stock_release'  => 0,'stock_receive' => 0,'qc' => '-','adjustment' => 0,'total_material' => 0,'total_realisasi' => 0,'selisih' => 0,
                'adj_batch'      => 0,'kesimpulan' => '','keputusan' => '',
            ],
            [
                'production_id'  => 'PRD-LST-0005','template_name' => 'Wall Paint Cream','formulasi' => 'F-002','basis' => 'Premium',
                'qty_jadwal'     => 250,'fk' => 'FK-005','jadwal' => '2026-07-30','produksi' => '2026-07-30','recanning' => '2026-07-30',
                'batch_no'       => 'BN-0425','no_spkp' => 'SPKP-2026-0022','date' => '2026-07-30','no_box_arsip' => 'BOX-C001',
                'tipe_product'   => 'Water Based','product_group' => 'Wall Paint','reference' => 'SPK-2026-0005','machine' => 'LINE-A1',
                'status'         => 'QC_PENDING','user_id' => 'USR-001','notes' => 'Menunggu QC finish',
                'stock_release'  => 260,'stock_receive' => 248,'qc' => 'PENDING','adjustment' => 2,'total_material' => 260,'total_realisasi' => 248,'selisih' => 12,
                'adj_batch'      => 0,'kesimpulan' => '','keputusan' => '',
            ],
            [
                'production_id'  => 'PRD-LST-0006','template_name' => 'Primer Putih','formulasi' => 'F-008','basis' => 'Standard',
                'qty_jadwal'     => 220,'fk' => 'FK-006','jadwal' => '2026-07-27','produksi' => '2026-07-27','recanning' => '2026-07-27',
                'batch_no'       => 'BN-0419','no_spkp' => 'SPKP-2026-0016','date' => '2026-07-27','no_box_arsip' => 'BOX-A003',
                'tipe_product'   => 'Water Based','product_group' => 'Primer','reference' => 'SPK-2026-0006','machine' => 'LINE-A2',
                'status'         => 'COMPLETED','user_id' => 'USR-002','notes' => 'Batch selesai',
                'stock_release'  => 230,'stock_receive' => 218,'qc' => 'PASS','adjustment' => 4,'total_material' => 230,'total_realisasi' => 218,'selisih' => 12,
                'adj_batch'      => 0,'kesimpulan' => 'Lolos QC','keputusan' => 'LULUS',
            ],
            [
                'production_id'  => 'PRD-LST-0007','template_name' => 'Cat Ekonomis','formulasi' => 'F-009','basis' => 'Standard',
                'qty_jadwal'     => 400,'fk' => 'FK-007','jadwal' => '2026-07-26','produksi' => '2026-07-26','recanning' => '2026-07-26',
                'batch_no'       => 'BN-0417','no_spkp' => 'SPKP-2026-0014','date' => '2026-07-26','no_box_arsip' => 'BOX-D001',
                'tipe_product'   => 'Water Based','product_group' => 'Ekonomis','reference' => 'SPK-2026-0007','machine' => 'LINE-B1',
                'status'         => 'COMPLETED','user_id' => 'USR-003','notes' => '',
                'stock_release'  => 420,'stock_receive' => 395,'qc' => 'PASS','adjustment' => 10,'total_material' => 420,'total_realisasi' => 395,'selisih' => 25,
                'adj_batch'      => 5,'kesimpulan' => 'Selisih masih dalam tolerance','keputusan' => 'LULUS',
            ],
            [
                'production_id'  => 'PRD-LST-0008','template_name' => 'Top Coat Glossy','formulasi' => 'F-010','basis' => 'Premium',
                'qty_jadwal'     => 150,'fk' => 'FK-008','jadwal' => '2026-07-31','produksi' => '','recanning' => '',
                'batch_no'       => '','no_spkp' => 'SPKP-2026-0023','date' => '2026-07-31','no_box_arsip' => '',
                'tipe_product'   => 'Solvent Based','product_group' => 'Top Coat','reference' => 'SPK-2026-0008','machine' => 'LINE-B2',
                'status'         => 'DRAFT','user_id' => 'USR-001','notes' => 'Jadwal minggu depan',
                'stock_release'  => 0,'stock_receive' => 0,'qc' => '-','adjustment' => 0,'total_material' => 0,'total_realisasi' => 0,'selisih' => 0,
                'adj_batch'      => 0,'kesimpulan' => '','keputusan' => '',
            ],
        ];

        foreach ($data as $item) {
            $this->store->create($item);
        }
    }

    public function index()
    {
        return view('production-planning.production-list.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['production_id'] ?? '', $q) !== false ||
                stripos($i['template_name'] ?? '', $q) !== false ||
                stripos($i['batch_no'] ?? '', $q) !== false ||
                stripos($i['no_spkp'] ?? '', $q) !== false
            );
        }

        if ($request->filled('filter_status') && $request->filter_status !== 'all') {
            $s = $request->filter_status;
            $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $s);
        }

        if ($request->filled('filter_machine') && $request->filter_machine !== 'all') {
            $m = $request->filter_machine;
            $data = array_filter($data, fn($i) => ($i['machine'] ?? '') === $m);
        }

        if ($request->filled('filter_date_from')) {
            $from = $request->filter_date_from;
            $data = array_filter($data, fn($i) => ($i['date'] ?? '') >= $from);
        }
        if ($request->filled('filter_date_to')) {
            $to = $request->filter_date_to;
            $data = array_filter($data, fn($i) => ($i['date'] ?? '') <= $to);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('status_badge', function ($r) {
                $map = [
                    'DRAFT'       => ['class' => 'bg-secondary',          'label' => 'Draft'],
                    'PLANNED'     => ['class' => 'bg-info text-dark',     'label' => 'Planned'],
                    'IN_PROGRESS' => ['class' => 'bg-primary',            'label' => 'In Progress'],
                    'QC_PENDING'  => ['class' => 'bg-warning text-dark',  'label' => 'QC Pending'],
                    'COMPLETED'   => ['class' => 'bg-success',            'label' => 'Completed'],
                ];
                $s = $r['status'] ?? 'DRAFT';
                return '<span class="badge '.($map[$s]['class'] ?? 'bg-secondary').'">'.($map[$s]['label'] ?? $s).'</span>';
            })
            ->addColumn('qc_badge', function ($r) {
                $map = ['PASS' => 'success', 'FAIL' => 'danger', 'PENDING' => 'warning text-dark'];
                $c = $map[$r['qc']] ?? 'secondary';
                return '<span class="badge bg-'.$c.'">'.($r['qc'] ?? '-').'</span>';
            })
            ->addColumn('action', function ($row) {
                $btns = '<div class="btn-group btn-group-sm">';
                $btns .= '<button type="button" class="btn btn-outline-primary btn-edit" data-id="'.$row['id'].'"><i class="bi bi-pencil"></i></button>';
                $btns .= '<button type="button" class="btn btn-outline-danger btn-delete" data-id="'.$row['id'].'"><i class="bi bi-trash"></i></button>';
                $btns .= '</div>';
                return $btns;
            })
            ->rawColumns(['status_badge', 'qc_badge', 'action'])->make(true);
    }

    public function store(Request $request)
    {
        $this->store->create($request->all());
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    public function show($id)
    {
        $d = $this->store->find($id);
        return $d ? response()->json(['success'=>true,'data'=>$d]) : response()->json(['message'=>'Data tidak ditemukan.'],404);
    }

    public function update(Request $request, $id)
    {
        $this->store->update($id, $request->all());
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}