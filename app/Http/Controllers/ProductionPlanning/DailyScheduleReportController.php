<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class DailyScheduleReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('daily-schedule-report');
        $this->initDummyData();
        View::share('activeMenu', 'daily-schedule-report');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $data = [
            ['tanggal'=>'2026-07-28','product_id'=>'PRD-001','name'=>'Kue Kering Vanila','base'=>'Water Based','formulasi'=>'F-001','batch_nr'=>'BN-0421','basis'=>'Standard','basis_kg'=>50,'total_basis_kg'=>200,'hasil_cm'=>195,'kode_mesin'=>'LINE-A1','status'=>'COMPLETED','realisasi'=>'Selesai','lead_time'=>6,'dateline'=>8,'on_time'=>'Yes'],
            ['tanggal'=>'2026-07-28','product_id'=>'PRD-002','name'=>'Kue Kering Cokelat','base'=>'Water Based','formulasi'=>'F-002','batch_nr'=>'BN-0422','basis'=>'Standard','basis_kg'=>40,'total_basis_kg'=>160,'hasil_cm'=>155,'kode_mesin'=>'LINE-A1','status'=>'COMPLETED','realisasi'=>'Selesai','lead_time'=>5,'dateline'=>7,'on_time'=>'Yes'],
            ['tanggal'=>'2026-07-28','product_id'=>'PRD-003','name'=>'Roti Gandum','base'=>'Water Based','formulasi'=>'F-003','batch_nr'=>'BN-0423','basis'=>'Premium','basis_kg'=>60,'total_basis_kg'=>300,'hasil_cm'=>290,'kode_mesin'=>'LINE-A2','status'=>'COMPLETED','realisasi'=>'Selesai','lead_time'=>7,'dateline'=>8,'on_time'=>'Yes'],
            ['tanggal'=>'2026-07-28','product_id'=>'PRD-004','name'=>'Roti Cokelat','base'=>'Water Based','formulasi'=>'F-004','batch_nr'=>'BN-0424','basis'=>'Standard','basis_kg'=>45,'total_basis_kg'=>180,'hasil_cm'=>170,'kode_mesin'=>'LINE-B1','status'=>'IN_PROGRESS','realisasi'=>'Proses','lead_time'=>0,'dateline'=>6,'on_time'=>'Yes'],
            ['tanggal'=>'2026-07-29','product_id'=>'PRD-005','name'=>'Roti Keju','base'=>'Solvent Based','formulasi'=>'F-005','batch_nr'=>'BN-0425','basis'=>'Premium','basis_kg'=>55,'total_basis_kg'=>220,'hasil_cm'=>0,'kode_mesin'=>'LINE-B2','status'=>'PLANNED','realisasi'=>'Belum','lead_time'=>0,'dateline'=>7,'on_time'=>'Yes'],
            ['tanggal'=>'2026-07-29','product_id'=>'PRD-001','name'=>'Kue Kering Vanila','base'=>'Water Based','formulasi'=>'F-001','batch_nr'=>'BN-0426','basis'=>'Standard','basis_kg'=>50,'total_basis_kg'=>250,'hasil_cm'=>0,'kode_mesin'=>'LINE-A1','status'=>'PLANNED','realisasi'=>'Belum','lead_time'=>0,'dateline'=>8,'on_time'=>'Yes'],
            ['tanggal'=>'2026-07-29','product_id'=>'PRD-003','name'=>'Roti Gandum','base'=>'Water Based','formulasi'=>'F-003','batch_nr'=>'BN-0427','basis'=>'Premium','basis_kg'=>60,'total_basis_kg'=>300,'hasil_cm'=>0,'kode_mesin'=>'LINE-A2','status'=>'DRAFT','realisasi'=>'Belum','lead_time'=>0,'dateline'=>8,'on_time'=>'Yes'],
            ['tanggal'=>'2026-07-27','product_id'=>'PRD-002','name'=>'Kue Kering Cokelat','base'=>'Water Based','formulasi'=>'F-002','batch_nr'=>'BN-0420','basis'=>'Standard','basis_kg'=>40,'total_basis_kg'=>160,'hasil_cm'=>140,'kode_mesin'=>'LINE-A1','status'=>'COMPLETED','realisasi'=>'Selesai','lead_time'=>9,'dateline'=>7,'on_time'=>'No'],
            ['tanggal'=>'2026-07-27','product_id'=>'PRD-004','name'=>'Roti Cokelat','base'=>'Water Based','formulasi'=>'F-004','batch_nr'=>'BN-0419','basis'=>'Standard','basis_kg'=>45,'total_basis_kg'=>180,'hasil_cm'=>178,'kode_mesin'=>'LINE-B1','status'=>'COMPLETED','realisasi'=>'Selesai','lead_time'=>6,'dateline'=>8,'on_time'=>'Yes'],
            ['tanggal'=>'2026-07-27','product_id'=>'PRD-005','name'=>'Roti Keju','base'=>'Solvent Based','formulasi'=>'F-005','batch_nr'=>'BN-0418','basis'=>'Premium','basis_kg'=>55,'total_basis_kg'=>220,'hasil_cm'=>215,'kode_mesin'=>'LINE-B2','status'=>'COMPLETED','realisasi'=>'Selesai','lead_time'=>8,'dateline'=>7,'on_time'=>'No'],
            ['tanggal'=>'2026-07-30','product_id'=>'PRD-001','name'=>'Kue Kering Vanila','base'=>'Water Based','formulasi'=>'F-001','batch_nr'=>'BN-0428','basis'=>'Standard','basis_kg'=>50,'total_basis_kg'=>200,'hasil_cm'=>0,'kode_mesin'=>'LINE-A2','status'=>'PLANNED','realisasi'=>'Belum','lead_time'=>0,'dateline'=>8,'on_time'=>'Yes'],
            ['tanggal'=>'2026-07-30','product_id'=>'PRD-003','name'=>'Roti Gandum','base'=>'Water Based','formulasi'=>'F-003','batch_nr'=>'BN-0429','basis'=>'Premium','basis_kg'=>60,'total_basis_kg'=>240,'hasil_cm'=>0,'kode_mesin'=>'LINE-A1','status'=>'DRAFT','realisasi'=>'Belum','lead_time'=>0,'dateline'=>7,'on_time'=>'Yes'],
        ];

        foreach ($data as $item) {
            $this->store->create($item);
        }
    }

    public function index()
    {
        return view('production-planning.daily-schedule-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['product_id'] ?? '', $q) !== false ||
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['kode_mesin'] ?? '', $q) !== false
            );
        }

        if ($request->filled('filter_date_from')) {
            $from = $request->filter_date_from;
            $data = array_filter($data, fn($i) => ($i['tanggal'] ?? '') >= $from);
        }
        if ($request->filled('filter_date_to')) {
            $to = $request->filter_date_to;
            $data = array_filter($data, fn($i) => ($i['tanggal'] ?? '') <= $to);
        }

        if ($request->filled('filter_mesin') && $request->filter_mesin !== 'all') {
            $m = $request->filter_mesin;
            $data = array_filter($data, fn($i) => ($i['kode_mesin'] ?? '') === $m);
        }

        if ($request->filled('filter_realisasi') && $request->filter_realisasi !== 'all') {
            $r = $request->filter_realisasi;
            $data = array_filter($data, fn($i) => ($i['realisasi'] ?? '') === $r);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('tanggal_fmt', fn($r) => \Carbon\Carbon::parse($r['tanggal'])->format('d/m/Y'))
            ->addColumn('basis_kg_fmt', fn($r) => number_format($r['basis_kg'],0,',','.'))
            ->addColumn('total_basis_kg_fmt', fn($r) => number_format($r['total_basis_kg'],0,',','.'))
            ->addColumn('hasil_cm_fmt', fn($r) => number_format($r['hasil_cm'],0,',','.'))
            ->addColumn('lead_time_fmt', fn($r) => $r['lead_time'].' jam')
            ->addColumn('status_badge', function ($r) {
                $map = [
                    'DRAFT'       => 'secondary',
                    'PLANNED'     => 'info',
                    'IN_PROGRESS' => 'primary',
                    'COMPLETED'   => 'success',
                ];
                $c = $map[$r['status']] ?? 'secondary';
                return '<span class="badge bg-'.$c.'">'.$r['status'].'</span>';
            })
            ->addColumn('on_time_badge', function ($r) {
                return $r['on_time'] === 'Yes'
                    ? '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>On Time</span>'
                    : '<span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Late</span>';
            })
            ->rawColumns(['status_badge', 'on_time_badge'])->make(true);
    }

    public function export(Request $request)
    {
        $data = $this->store->all();
        $filename = 'daily-schedule-report-'.date('Y-m-d').'.csv';

        $headers = ['Content-Type: text/csv','Content-Disposition: attachment;filename="'.$filename.'"'];
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal','Product ID','Name','Base','Formulasi','Batch NR','Basis','Basis (Kg)','Total Basis (Kg)','Hasil CM','Kode Mesin','Status','Realisasi','Lead Time (jam)','Dateline','On Time']);
            foreach ($data as $row) {
                fputcsv($file, [$row['tanggal'],$row['product_id'],$row['name'],$row['base'],$row['formulasi'],$row['batch_nr'],$row['basis'],$row['basis_kg'],$row['total_basis_kg'],$row['hasil_cm'],$row['kode_mesin'],$row['status'],$row['realisasi'],$row['lead_time'],$row['dateline'],$row['on_time']]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}