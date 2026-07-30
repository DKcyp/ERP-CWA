<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class RealisasiJadwalBasePerMesinReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('realisasi-jadwal-base-per-mesin-report');
        $this->initDummyData();
        View::share('activeMenu', 'realisasi-jadwal-base-per-mesin-report');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $products = ['Wall Paint White 20L','Wall Paint Cream 10L','Primer Grey 5L','Top Coat Clear 15L','Cat Ekonomis 5L'];
        $machines = ['M-01','M-02','M-03','M-04'];
        $types = ['Water Based','Solvent Based'];
        $operators = ['Budi Santoso','Andi Kurniawan','Citra Dewi','Dedi Kuswanto','Eka Putri','Fajar Nugroho','Gilang Ramadhan','Hendra Wijaya'];
        $shifts = ['Shift 1','Shift 2','Shift 3'];

        $data = [];
        for ($d = 0; $d < 5; $d++) {
            $date = date('Y-m-d', strtotime("2026-07-26 +{$d} days"));
            foreach ($machines as $mi => $machine) {
                if (rand(0, 3) === 0) continue;
                $shift = $shifts[array_rand($shifts)];
                $type = $types[array_rand($types)];
                $products_used = array_rand($products, rand(1, 2));
                if (!is_array($products_used)) $products_used = [$products_used];
                foreach ($products_used as $pi) {
                    $basis = rand(200, 600);
                    $realisasi = $basis + rand(-20, 30);
                    $durAir = rand(5, 15);
                    $durGiling = rand(30, 90);
                    $durCekHalus = rand(10, 25);
                    $durCekAkhir = rand(8, 20);
                    $durTotal = $durAir + $durGiling + $durCekHalus + $durCekAkhir;

                    $data[] = [
                        'doc_id' => 'RJB-'.date('Ymd', strtotime($date)).'-'.str_pad($d * 4 + $mi + 1, 3, '0', STR_PAD_LEFT),
                        'date' => $date,
                        'prod_date' => $date,
                        'shift' => $shift,
                        'machine' => $machine,
                        'type' => $type,
                        'nama_product' => $products[$pi],
                        'batch_no' => 'ADN-'.str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT),
                        'total_basis_kg' => $basis,
                        'realisasi_kg' => $realisasi,
                        'duration_pengisian_air' => $durAir,
                        'duration_proses_giling' => $durGiling,
                        'duration_cek_kehalusan' => $durCekHalus,
                        'duration_cek_akhir' => $durCekAkhir,
                        'duration_total_process' => $durTotal,
                        'duration_total_jam' => floor($durTotal / 60),
                        'duration_total_menit' => $durTotal % 60,
                        'operator' => $operators[array_rand($operators)],
                        'notes' => $durTotal > 120 ? 'Durasi panjang, cek mesin' : '',
                    ];
                }
            }
        }
        foreach ($data as $item) { $this->store->create($item); }
    }

    public function index()
    {
        return view('production-planning.realisasi-jadwal-base-per-mesin-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['doc_id'] ?? '', $q) !== false ||
                stripos($i['machine'] ?? '', $q) !== false ||
                stripos($i['nama_product'] ?? '', $q) !== false ||
                stripos($i['operator'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_date_from')) {
            $from = $request->filter_date_from;
            $data = array_filter($data, fn($i) => ($i['prod_date'] ?? '') >= $from);
        }
        if ($request->filled('filter_date_to')) {
            $to = $request->filter_date_to;
            $data = array_filter($data, fn($i) => ($i['prod_date'] ?? '') <= $to);
        }
        if ($request->filled('filter_shift') && $request->filter_shift !== 'all') {
            $s = $request->filter_shift;
            $data = array_filter($data, fn($i) => ($i['shift'] ?? '') === $s);
        }
        if ($request->filled('filter_machine') && $request->filter_machine !== 'all') {
            $m = $request->filter_machine;
            $data = array_filter($data, fn($i) => ($i['machine'] ?? '') === $m);
        }
        if ($request->filled('filter_type') && $request->filter_type !== 'all') {
            $t = $request->filter_type;
            $data = array_filter($data, fn($i) => ($i['type'] ?? '') === $t);
        }
        if ($request->filled('filter_operator') && $request->filter_operator !== 'all') {
            $op = $request->filter_operator;
            $data = array_filter($data, fn($i) => ($i['operator'] ?? '') === $op);
        }

        $data = array_values($data);

        $totalDurasi = array_sum(array_column($data, 'duration_total_process'));
        $totalRealisasi = array_sum(array_column($data, 'realisasi_kg'));
        $totalBasis = array_sum(array_column($data, 'total_basis_kg'));
        $totalJam = floor($totalDurasi / 60);
        $totalMenit = $totalDurasi % 60;
        $effisiensi = $totalBasis > 0 ? round(($totalRealisasi / $totalBasis) * 100, 1) : 0;

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('prod_date_fmt', fn($r) => \Carbon\Carbon::parse($r['prod_date'])->format('d/m/Y'))
            ->addColumn('basis_fmt', fn($r) => number_format($r['total_basis_kg'], 0, ',', '.'))
            ->addColumn('realisasi_fmt', fn($r) => number_format($r['realisasi_kg'], 0, ',', '.'))
            ->addColumn('durasi_detail', function ($r) {
                return '<small class="text-muted">'.$r['duration_pengisian_air'].' / '.$r['duration_proses_giling'].' / '.$r['duration_cek_kehalusan'].' / '.$r['duration_cek_akhir'].'<br>'.
                    '<span class="text-primary fw-semibold">Air / Giling / Cek Halus / Cek Akhir (mnt)</span></small>';
            })
            ->addColumn('durasi_total_fmt', function ($r) {
                $jam = floor($r['duration_total_process'] / 60);
                $mnt = $r['duration_total_process'] % 60;
                $color = $r['duration_total_process'] > 120 ? 'danger' : ($r['duration_total_process'] > 90 ? 'warning' : 'success');
                return '<span class="badge bg-'.$color.'">'.$jam.'j '.$mnt.'m</span>';
            })
            ->rawColumns(['basis_fmt', 'realisasi_fmt', 'durasi_detail', 'durasi_total_fmt'])
            ->with(['summary' => [
                'total_jam' => $totalJam, 'total_menit' => $totalMenit,
                'total_realisasi' => $totalRealisasi, 'total_basis' => $totalBasis,
                'effisiensi' => $effisiensi,
            ]])
            ->make(true);
    }

    public function export(Request $request)
    {
        $data = $this->store->all();
        $filename = 'realisasi-jadwal-base-per-mesin-'.date('Y-m-d').'.csv';

        $headers = ['Content-Type: text/csv','Content-Disposition: attachment;filename="'.$filename.'"'];
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Doc ID','Prod Date','Shift','Machine','Type','Product','Batch No','Basis (KG)','Realisasi (KG)','Air (mnt)','Giling (mnt)','Cek Halus (mnt)','Cek Akhir (mnt)','Total Durasi (mnt)','Operator','Notes']);
            foreach ($data as $row) {
                fputcsv($file, [$row['doc_id'],$row['prod_date'],$row['shift'],$row['machine'],$row['type'],$row['nama_product'],$row['batch_no'],$row['total_basis_kg'],$row['realisasi_kg'],$row['duration_pengisian_air'],$row['duration_proses_giling'],$row['duration_cek_kehalusan'],$row['duration_cek_akhir'],$row['duration_total_process'],$row['operator'],$row['notes']]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}