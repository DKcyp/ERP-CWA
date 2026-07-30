<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class RealisasiJadwalPastaReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('realisasi-jadwal-pasta-report');
        $this->initDummyData();
        View::share('activeMenu', 'realisasi-jadwal-pasta-report');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $pasta = [
            ['kode'=>'PGS-RED-001','name'=>'Pigment Red Oxide'],
            ['kode'=>'PGS-YEL-002','name'=>'Pigment Yellow Ochre'],
            ['kode'=>'PGS-BLU-003','name'=>'Pigment Blue Ultramarine'],
            ['kode'=>'PGS-GRN-004','name'=>'Pigment Green Chrome'],
            ['kode'=>'PGS-WHT-005','name'=>'Pigment White Titanium'],
            ['kode'=>'PGS-BLK-006','name'=>'Pigment Black Carbon'],
            ['kode'=>'PGS-CRM-007','name'=>'Pigment Cream'],
            ['kode'=>'PGS-ORG-008','name'=>'Pigment Orange'],
        ];
        $machines = ['P-01','P-02','P-03'];
        $operators = ['Rina Sari','Tono Widodo','Siti Aminah','Joko Prasetyo','Maya Putri','Andi Lesmana'];
        $types = ['Water Based','Solvent Based'];
        $shifts = ['Shift 1','Shift 2','Shift 3'];
        $statuses = ['Selesai','Proses','Tertunda'];

        $data = [];
        for ($d = 0; $d < 5; $d++) {
            $date = date('Y-m-d', strtotime("2026-07-26 +{$d} days"));
            for ($r = 0; $r < rand(2, 5); $r++) {
                $p = $pasta[array_rand($pasta)];
                $basis = rand(50, 250);
                $realisasi = $basis + rand(-10, 15);
                $selisih = $realisasi - $basis;
                $pct = $basis > 0 ? round(($realisasi / $basis) * 100, 1) : 0;
                $jadwalDate = date('Y-m-d', strtotime($date . ' -'.rand(0,2).' days'));
                $deadlineDate = date('Y-m-d', strtotime($jadwalDate.' +'.rand(2,5).' days'));
                $leadTimeHari = rand(1, 5);
                $mulaiH = rand(6, 18); $selesaiH = $mulaiH + rand(2, 5);
                $waktuTunggu = rand(0, 120);
                $today = strtotime($date);
                $dl = strtotime($deadlineDate);
                $statusPencapaian = $today <= $dl ? 'Tepat Waktu' : 'Terlambat';

                $data[] = [
                    'doc_id' => 'RJP-'.date('Ymd', strtotime($date)).'-'.str_pad($d * 5 + $r + 1, 3, '0', STR_PAD_LEFT),
                    'date' => $date, 'shift' => $shifts[array_rand($shifts)],
                    'type' => $types[array_rand($types)],
                    'kode_pasta' => $p['kode'], 'name' => $p['name'],
                    'batch' => 'PGS-ADN-'.str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT),
                    'mesin' => $machines[array_rand($machines)],
                    'total_basis_kg' => $basis, 'realisasi_kg' => $realisasi,
                    'selisih_kg' => $selisih, 'percentage' => $pct,
                    'mulai' => sprintf('%02d:00', $mulaiH), 'selesai' => sprintf('%02d:00', min($selesaiH, 23)),
                    'waktu_tunggu_jam' => intdiv($waktuTunggu, 60), 'waktu_tunggu_menit' => $waktuTunggu % 60,
                    'waktu_tunggu_total' => $waktuTunggu,
                    'operator' => $operators[array_rand($operators)],
                    'tgl_jadwal' => $jadwalDate, 'lead_time' => $leadTimeHari.' hari',
                    'lead_time_hari' => $leadTimeHari,
                    'dateline' => $deadlineDate, 'status_pencapaian' => $statusPencapaian,
                    'notes' => $pct < 95 ? 'Percentage rendah' : ($statusPencapaian === 'Terlambat' ? 'Melewati dateline' : ''),
                ];
            }
        }
        foreach ($data as $item) { $this->store->create($item); }
    }

    public function index()
    {
        return view('production-planning.realisasi-jadwal-pasta-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['doc_id'] ?? '', $q) !== false ||
                stripos($i['kode_pasta'] ?? '', $q) !== false ||
                stripos($i['mesin'] ?? '', $q) !== false ||
                stripos($i['operator'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_date_from')) $data = array_filter($data, fn($i) => ($i['date'] ?? '') >= $request->filter_date_from);
        if ($request->filled('filter_date_to')) $data = array_filter($data, fn($i) => ($i['date'] ?? '') <= $request->filter_date_to);
        if ($request->filled('filter_shift') && $request->filter_shift !== 'all') $data = array_filter($data, fn($i) => ($i['shift'] ?? '') === $request->filter_shift);
        if ($request->filled('filter_type') && $request->filter_type !== 'all') $data = array_filter($data, fn($i) => ($i['type'] ?? '') === $request->filter_type);
        if ($request->filled('filter_mesin') && $request->filter_mesin !== 'all') $data = array_filter($data, fn($i) => ($i['mesin'] ?? '') === $request->filter_mesin);
        if ($request->filled('filter_operator') && $request->filter_operator !== 'all') $data = array_filter($data, fn($i) => ($i['operator'] ?? '') === $request->filter_operator);
        if ($request->filled('filter_status') && $request->filter_status !== 'all') $data = array_filter($data, fn($i) => ($i['status_pencapaian'] ?? '') === $request->filter_status);

        $data = array_values($data);

        $totalBasis = array_sum(array_column($data, 'total_basis_kg'));
        $totalRealisasi = array_sum(array_column($data, 'realisasi_kg'));
        $avgLeadTime = count($data) > 0 ? round(array_sum(array_column($data, 'lead_time_hari')) / count($data), 1) : 0;
        $avgWaktuTunggu = count($data) > 0 ? round(array_sum(array_column($data, 'waktu_tunggu_total')) / count($data)) : 0;
        $tepattime = count(array_filter($data, fn($i) => ($i['status_pencapaian'] ?? '') === 'Tepat Waktu'));

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('jadwal_fmt', fn($r) => \Carbon\Carbon::parse($r['tgl_jadwal'])->format('d/m/Y'))
            ->addColumn('deadline_fmt', fn($r) => \Carbon\Carbon::parse($r['dateline'])->format('d/m/Y'))
            ->addColumn('basis_fmt', fn($r) => number_format($r['total_basis_kg'], 0, ',', '.'))
            ->addColumn('realisasi_fmt', fn($r) => number_format($r['realisasi_kg'], 0, ',', '.'))
            ->addColumn('selisih_badge', function ($r) {
                $v = $r['selisih_kg'];
                if ($v > 0) return '<span class="badge bg-success">+'.number_format($v, 0, ',', '.').'</span>';
                if ($v < 0) return '<span class="badge bg-danger">'.number_format($v, 0, ',', '.').'</span>';
                return '<span class="badge bg-secondary">0</span>';
            })
            ->addColumn('pct_badge', function ($r) {
                $p = $r['percentage'];
                if ($p >= 100) return '<span class="badge bg-success">'.$p.'%</span>';
                if ($p >= 95) return '<span class="badge bg-warning text-dark">'.$p.'%</span>';
                return '<span class="badge bg-danger">'.$p.'%</span>';
            })
            ->addColumn('jam', fn($r) => $r['mulai'].' - '.$r['selesai'])
            ->addColumn('tunggu_fmt', fn($r) => $r['waktu_tunggu_jam'].'j '.$r['waktu_tunggu_menit'].'m')
            ->addColumn('status_badge', function ($r) {
                $s = $r['status_pencapaian'];
                return $s === 'Tepat Waktu'
                    ? '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Tepat Waktu</span>'
                    : '<span class="badge bg-danger"><i class="bi bi-clock-history me-1"></i>Terlambat</span>';
            })
            ->rawColumns(['basis_fmt','realisasi_fmt','selisih_badge','pct_badge','status_badge'])
            ->with(['summary' => [
                'total_basis' => $totalBasis, 'total_realisasi' => $totalRealisasi,
                'avg_lead_time' => $avgLeadTime, 'avg_waktu_tunggu' => $avgWaktuTunggu,
                'total_records' => count($data), 'tepattime' => $tepattime,
            ]])
            ->make(true);
    }

    public function export(Request $request)
    {
        $data = $this->store->all();
        $filename = 'realisasi-jadwal-pasta-report-'.date('Y-m-d').'.csv';
        $headers = ['Content-Type: text/csv','Content-Disposition: attachment;filename="'.$filename.'"'];
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Doc ID','Date','Shift','Type','Kode Pasta','Name','Batch','Mesin','Basis (KG)','Realisasi (KG)','Selisih','Percentage','Mulai','Selesai','Wkt Tunggu','Operator','Tgl Jadwal','Lead Time','Dateline','Status Pencapaian','Notes']);
            foreach ($data as $r) {
                fputcsv($file, [$r['doc_id'],$r['date'],$r['shift'],$r['type'],$r['kode_pasta'],$r['name'],$r['batch'],$r['mesin'],$r['total_basis_kg'],$r['realisasi_kg'],$r['selisih_kg'],$r['percentage'],$r['mulai'],$r['selesai'],$r['waktu_tunggu_jam'].'j '.$r['waktu_tunggu_menit'].'m',$r['operator'],$r['tgl_jadwal'],$r['lead_time'],$r['dateline'],$r['status_pencapaian'],$r['notes']]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}