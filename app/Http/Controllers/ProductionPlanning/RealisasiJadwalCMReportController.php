<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class RealisasiJadwalCMReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('realisasi-jadwal-cm-report');
        $this->initDummyData();
        View::share('activeMenu', 'realisasi-jadwal-cm-report');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $products = ['CM Wall Paint White 20L','CM Wall Paint Cream 10L','CM Primer Grey 5L','CM Top Coat Blue 15L','CM Cat Ekonomis Red 5L','CM Wall Paint Green 10L'];
        $codes = ['CM-W-001','CM-W-002','CM-P-001','CM-TC-001','CM-E-001','CM-W-003'];
        $machines = ['CM-01','CM-02','CM-03'];
        $operators = ['Rina Sari','Tono Widodo','Siti Aminah','Joko Prasetyo','Maya Putri','Andi Lesmana'];
        $types = ['Water Based','Solvent Based'];
        $categories = ['Pusat','Cabang'];
        $shifts = ['Shift 1','Shift 2','Shift 3'];

        $data = [];
        for ($d = 0; $d < 5; $d++) {
            $date = date('Y-m-d', strtotime("2026-07-26 +{$d} days"));
            foreach ($machines as $mi => $machine) {
                if (rand(0, 2) === 0) continue;
                $shift = $shifts[array_rand($shifts)];
                $type = $types[array_rand($types)];
                $cat = $categories[array_rand($categories)];
                $products_used = array_rand($products, rand(1, 2));
                if (!is_array($products_used)) $products_used = [$products_used];
                foreach ($products_used as $pi) {
                    $basis = rand(100, 400);
                    $variance = rand(-20, 25);
                    $realisasi = $basis + $variance;
                    $eff = round(($realisasi / $basis) * 100, 1);
                    $startHour = match($shift) { 'Shift 1' => 6, 'Shift 2' => 14, 'Shift 3' => 22, default => 6 };
                    $mulai = sprintf('%02d:00', $startHour + rand(0, 1));
                    $selesai = sprintf('%02d:%02d', $startHour + rand(5, 7), rand(0, 59));

                    $data[] = [
                        'doc_id' => 'RJCM-'.date('Ymd', strtotime($date)).'-'.str_pad($d * 3 + $mi + 1, 3, '0', STR_PAD_LEFT),
                        'date' => $date,
                        'prod_date' => $date,
                        'shift' => $shift,
                        'type' => $type,
                        'schedule_category' => $cat,
                        'nama_product' => $products[$pi],
                        'kode_warna' => $codes[$pi],
                        'batch_no' => 'CM-ADN-'.str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT),
                        'mesin' => $machine,
                        'total_basis_kg' => $basis,
                        'realisasi_kg' => $realisasi,
                        'variance_kg' => $variance,
                        'efficiency_percent' => $eff,
                        'mulai' => $mulai,
                        'selesai' => $selesai,
                        'operator' => $operators[array_rand($operators)],
                        'notes' => $variance < -10 ? 'Di bawah target' : ($variance > 20 ? 'Melebihi target' : ''),
                    ];
                }
            }
        }
        foreach ($data as $item) { $this->store->create($item); }
    }

    public function index()
    {
        return view('production-planning.realisasi-jadwal-cm-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['doc_id'] ?? '', $q) !== false ||
                stripos($i['kode_warna'] ?? '', $q) !== false ||
                stripos($i['mesin'] ?? '', $q) !== false ||
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
        if ($request->filled('filter_type') && $request->filter_type !== 'all') {
            $t = $request->filter_type;
            $data = array_filter($data, fn($i) => ($i['type'] ?? '') === $t);
        }
        if ($request->filled('filter_category') && $request->filter_category !== 'all') {
            $c = $request->filter_category;
            $data = array_filter($data, fn($i) => ($i['schedule_category'] ?? '') === $c);
        }
        if ($request->filled('filter_mesin') && $request->filter_mesin !== 'all') {
            $m = $request->filter_mesin;
            $data = array_filter($data, fn($i) => ($i['mesin'] ?? '') === $m);
        }
        if ($request->filled('filter_operator') && $request->filter_operator !== 'all') {
            $op = $request->filter_operator;
            $data = array_filter($data, fn($i) => ($i['operator'] ?? '') === $op);
        }

        $data = array_values($data);

        $totalBasis = array_sum(array_column($data, 'total_basis_kg'));
        $totalRealisasi = array_sum(array_column($data, 'realisasi_kg'));
        $totalVariance = array_sum(array_column($data, 'variance_kg'));
        $avgEff = count($data) > 0 ? round(array_sum(array_column($data, 'efficiency_percent')) / count($data), 1) : 0;
        $uniqueColors = count(array_unique(array_column($data, 'kode_warna')));

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('prod_date_fmt', fn($r) => \Carbon\Carbon::parse($r['prod_date'])->format('d/m/Y'))
            ->addColumn('basis_fmt', fn($r) => number_format($r['total_basis_kg'], 0, ',', '.'))
            ->addColumn('realisasi_fmt', fn($r) => number_format($r['realisasi_kg'], 0, ',', '.'))
            ->addColumn('variance_badge', function ($r) {
                $v = $r['variance_kg'];
                if ($v > 0) return '<span class="badge bg-success"><i class="bi bi-arrow-up-short me-1"></i>+'.number_format($v, 0, ',', '.').'</span>';
                if ($v < 0) return '<span class="badge bg-danger"><i class="bi bi-arrow-down-short me-1"></i>'.number_format($v, 0, ',', '.').'</span>';
                return '<span class="badge bg-secondary">0</span>';
            })
            ->addColumn('eff_badge', function ($r) {
                $e = $r['efficiency_percent'];
                if ($e >= 100) return '<span class="badge bg-success">'.$e.'%</span>';
                if ($e >= 95) return '<span class="badge bg-warning text-dark">'.$e.'%</span>';
                return '<span class="badge bg-danger">'.$e.'%</span>';
            })
            ->addColumn('jam', fn($r) => $r['mulai'].' - '.$r['selesai'])
            ->rawColumns(['basis_fmt', 'realisasi_fmt', 'variance_badge', 'eff_badge'])
            ->with(['summary' => [
                'total_basis' => $totalBasis, 'total_realisasi' => $totalRealisasi,
                'total_variance' => $totalVariance, 'avg_eff' => $avgEff,
                'unique_colors' => $uniqueColors,
            ]])
            ->make(true);
    }

    public function export(Request $request)
    {
        $data = $this->store->all();
        $filename = 'realisasi-jadwal-cm-report-'.date('Y-m-d').'.csv';

        $headers = ['Content-Type: text/csv','Content-Disposition: attachment;filename="'.$filename.'"'];
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Doc ID','Prod Date','Shift','Type','Jadwal','Product','Kode Warna','Batch No','Mesin','Basis (KG)','Realisasi (KG)','Variance (KG)','Efficiency (%)','Mulai','Selesai','Operator','Notes']);
            foreach ($data as $row) {
                fputcsv($file, [$row['doc_id'],$row['prod_date'],$row['shift'],$row['type'],$row['schedule_category'],$row['nama_product'],$row['kode_warna'],$row['batch_no'],$row['mesin'],$row['total_basis_kg'],$row['realisasi_kg'],$row['variance_kg'],$row['efficiency_percent'],$row['mulai'],$row['selesai'],$row['operator'],$row['notes']]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}