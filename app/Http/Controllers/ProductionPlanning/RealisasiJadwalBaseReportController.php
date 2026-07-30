<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class RealisasiJadwalBaseReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('realisasi-jadwal-base-report');
        $this->initDummyData();
        View::share('activeMenu', 'realisasi-jadwal-base-report');
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
                    $variance = rand(-30, 40);
                    $realisasi = $basis + $variance;
                    $eff = round(($realisasi / $basis) * 100, 1);
                    $startHour = match($shift) { 'Shift 1' => 6, 'Shift 2' => 14, 'Shift 3' => 22, default => 6 };
                    $mulai = sprintf('%02d:00', $startHour + rand(0, 1));
                    $selesai = sprintf('%02d:%02d', $startHour + rand(6, 8), rand(0, 59));

                    $data[] = [
                        'doc_id' => 'RJB-'.date('Ymd', strtotime($date)).'-'.str_pad($d * 4 + $mi + 1, 3, '0', STR_PAD_LEFT),
                        'date' => $date,
                        'prod_date' => $date,
                        'shift' => $shift,
                        'type' => $type,
                        'nama_product' => $products[$pi],
                        'batch_no' => 'ADN-'.str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT),
                        'mesin' => $machine,
                        'total_basis_kg' => $basis,
                        'realisasi_kg' => $realisasi,
                        'variance_kg' => $variance,
                        'efficiency_percent' => $eff,
                        'mulai' => $mulai,
                        'selesai' => $selesai,
                        'operator' => $operators[array_rand($operators)],
                        'notes' => $variance < -10 ? 'Di bawah target' : ($variance > 30 ? 'Melebihi target' : ''),
                    ];
                }
            }
        }
        foreach ($data as $item) { $this->store->create($item); }
    }

    public function index()
    {
        return view('production-planning.realisasi-jadwal-base-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['doc_id'] ?? '', $q) !== false ||
                stripos($i['mesin'] ?? '', $q) !== false ||
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
        if ($request->filled('filter_type') && $request->filter_type !== 'all') {
            $t = $request->filter_type;
            $data = array_filter($data, fn($i) => ($i['type'] ?? '') === $t);
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
            ->rawColumns(['basis_fmt', 'realisasi_fmt', 'variance_badge', 'eff_badge'])
            ->with(['summary' => [
                'total_basis' => $totalBasis, 'total_realisasi' => $totalRealisasi,
                'total_variance' => $totalVariance, 'avg_eff' => $avgEff,
            ]])
            ->make(true);
    }

    public function export(Request $request)
    {
        $data = $this->store->all();
        $filename = 'realisasi-jadwal-base-report-'.date('Y-m-d').'.csv';

        $headers = ['Content-Type: text/csv','Content-Disposition: attachment;filename="'.$filename.'"'];
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Doc ID','Prod Date','Shift','Type','Product','Batch No','Mesin','Basis (KG)','Realisasi (KG)','Variance (KG)','Efficiency (%)','Mulai','Selesai','Operator','Notes']);
            foreach ($data as $row) {
                fputcsv($file, [$row['doc_id'],$row['prod_date'],$row['shift'],$row['type'],$row['nama_product'],$row['batch_no'],$row['mesin'],$row['total_basis_kg'],$row['realisasi_kg'],$row['variance_kg'],$row['efficiency_percent'],$row['mulai'],$row['selesai'],$row['operator'],$row['notes']]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}