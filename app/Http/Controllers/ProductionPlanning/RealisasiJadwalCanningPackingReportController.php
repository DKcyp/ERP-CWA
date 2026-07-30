<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class RealisasiJadwalCanningPackingReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('realisasi-jadwal-canning-packing-report');
        $this->initDummyData();
        View::share('activeMenu', 'realisasi-jadwal-canning-packing-report');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $colors = [
            ['kode'=>'CM-W-001','warna'=>'Putih'],['kode'=>'CM-W-002','warna'=>'Cream'],
            ['kode'=>'CM-P-001','warna'=>'Abu-abu'],['kode'=>'CM-TC-001','warna'=>'Biru'],
            ['kode'=>'CM-E-001','warna'=>'Merah'],['kode'=>'CM-W-003','warna'=>'Hijau'],
        ];
        $opCanning = ['Rina Sari','Tono Widodo','Siti Aminah','Joko Prasetyo'];
        $opPacking = ['Maya Putri','Andi Lesmana','Rina Sari','Dedi Kuswanto'];
        $types = ['Water Based','Solvent Based'];
        $categories = ['Pusat','Cabang'];
        $shifts = ['Shift 1','Shift 2','Shift 3'];

        $data = [];
        for ($d = 0; $d < 5; $d++) {
            $date = date('Y-m-d', strtotime("2026-07-26 +{$d} days"));
            for ($r = 0; $r < rand(2, 4); $r++) {
                $c = $colors[array_rand($colors)];
                $basis = rand(100, 350);
                $cm = $basis + rand(-10, 15);
                $canning = $cm + rand(-5, 10);
                $yield = round(($canning / $basis) * 100, 1);
                $k01 = rand(0,20); $k02 = rand(0,30); $k04 = rand(10,50); $k045 = rand(0,25); $k09 = rand(5,20);
                $kp = rand(0,15); $galon = rand(5,30); $pail = rand(0,10); $liter = rand(0,8); $k500 = rand(0,20); $k1l = rand(0,15);
                $ba = rand(2000,5000); $bi = $ba + rand(-50,50);
                $sel = $bi - $ba;
                $selBadge = $sel > 10 ? 'text-danger' : ($sel < -10 ? 'text-warning' : 'text-success');

                $data[] = [
                    'doc_id' => 'RJCP-'.date('Ymd', strtotime($date)).'-'.str_pad($d * 4 + $r + 1, 3, '0', STR_PAD_LEFT),
                    'date' => $date, 'prod_date' => $date,
                    'shift' => $shifts[array_rand($shifts)],
                    'type' => $types[array_rand($types)],
                    'schedule_category' => $categories[array_rand($categories)],
                    'kode_warna' => $c['kode'], 'warna' => $c['warna'],
                    'batch_no' => 'CP-ADN-'.str_pad(rand(100,999), 3, '0', STR_PAD_LEFT),
                    'basis_kg' => $basis, 'realisasi_cm_kg' => $cm,
                    'kaleng_01l' => $k01, 'kaleng_02l' => $k02, 'kaleng_04l' => $k04,
                    'kaleng_045l' => $k045, 'kaleng_09l' => $k09, 'kaleng_pcs' => $kp,
                    'galon_pcs' => $galon, 'pail_pcs' => $pail, 'liter_pcs' => $liter,
                    'kaleng_500ml' => $k500, 'kaleng_1l' => $k1l,
                    'detail_kemasan_summary' => 'K:'.($k01+$k02+$k04+$k045+$k09+$kp+$k500+$k1l).' G:'.$galon.' P:'.$pail.' L:'.$liter,
                    'total_pcs' => $k01+$k02+$k04+$k045+$k09+$kp+$k500+$k1l+$galon+$pail+$liter,
                    'realisasi_canning_kg' => $canning, 'yield_percent' => $yield,
                    'berat_awal' => $ba, 'berat_akhir' => $bi, 'selisih_kg' => $sel, 'selisih_badge' => $selBadge,
                    'operator_canning' => $opCanning[array_rand($opCanning)],
                    'operator_packing' => $opPacking[array_rand($opPacking)],
                    'notes' => $yield < 95 ? 'Yield rendah' : '',
                ];
            }
        }
        foreach ($data as $item) { $this->store->create($item); }
    }

    public function index()
    {
        return view('production-planning.realisasi-jadwal-canning-packing-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['doc_id'] ?? '', $q) !== false ||
                stripos($i['kode_warna'] ?? '', $q) !== false ||
                stripos($i['operator_canning'] ?? '', $q) !== false ||
                stripos($i['operator_packing'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_date_from')) $data = array_filter($data, fn($i) => ($i['prod_date'] ?? '') >= $request->filter_date_from);
        if ($request->filled('filter_date_to')) $data = array_filter($data, fn($i) => ($i['prod_date'] ?? '') <= $request->filter_date_to);
        if ($request->filled('filter_shift') && $request->filter_shift !== 'all') $data = array_filter($data, fn($i) => ($i['shift'] ?? '') === $request->filter_shift);
        if ($request->filled('filter_type') && $request->filter_type !== 'all') $data = array_filter($data, fn($i) => ($i['type'] ?? '') === $request->filter_type);
        if ($request->filled('filter_category') && $request->filter_category !== 'all') $data = array_filter($data, fn($i) => ($i['schedule_category'] ?? '') === $request->filter_category);
        if ($request->filled('filter_operator') && $request->filter_operator !== 'all') {
            $op = $request->filter_operator;
            $data = array_filter($data, fn($i) => ($i['operator_canning'] ?? '') === $op || ($i['operator_packing'] ?? '') === $op);
        }

        $data = array_values($data);

        $totalBasis = array_sum(array_column($data, 'basis_kg'));
        $totalCanning = array_sum(array_column($data, 'realisasi_canning_kg'));
        $totalPcs = array_sum(array_column($data, 'total_pcs'));
        $avgYield = count($data) > 0 ? round(array_sum(array_column($data, 'yield_percent')) / count($data), 1) : 0;
        $totalSelisih = array_sum(array_column($data, 'selisih_kg'));

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('prod_date_fmt', fn($r) => \Carbon\Carbon::parse($r['prod_date'])->format('d/m/Y'))
            ->addColumn('basis_fmt', fn($r) => number_format($r['basis_kg'], 0, ',', '.'))
            ->addColumn('cm_fmt', fn($r) => number_format($r['realisasi_cm_kg'], 0, ',', '.'))
            ->addColumn('canning_fmt', fn($r) => number_format($r['realisasi_canning_kg'], 0, ',', '.'))
            ->addColumn('yield_badge', function ($r) {
                $y = $r['yield_percent'];
                if ($y >= 100) return '<span class="badge bg-success">'.$y.'%</span>';
                if ($y >= 95) return '<span class="badge bg-warning text-dark">'.$y.'%</span>';
                return '<span class="badge bg-danger">'.$y.'%</span>';
            })
            ->addColumn('selisih_fmt', function ($r) {
                $v = $r['selisih_kg'];
                $cls = $r['selisih_badge'] ?? '';
                $sign = $v > 0 ? '+' : '';
                return '<span class="'.$cls.' fw-semibold">'.$sign.number_format($v, 0, ',', '.').'</span>';
            })
            ->addColumn('ba_fmt', fn($r) => number_format($r['berat_awal'], 0, ',', '.'))
            ->addColumn('bi_fmt', fn($r) => number_format($r['berat_akhir'], 0, ',', '.'))
            ->rawColumns(['basis_fmt','cm_fmt','canning_fmt','yield_badge','selisih_fmt','ba_fmt','bi_fmt'])
            ->with(['summary' => [
                'total_basis' => $totalBasis, 'total_canning' => $totalCanning,
                'total_pcs' => $totalPcs, 'avg_yield' => $avgYield, 'total_selisih' => $totalSelisih,
            ]])
            ->make(true);
    }

    public function export(Request $request)
    {
        $data = $this->store->all();
        $filename = 'realisasi-jadwal-canning-packing-report-'.date('Y-m-d').'.csv';
        $headers = ['Content-Type: text/csv','Content-Disposition: attachment;filename="'.$filename.'"'];
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Doc ID','Prod Date','Shift','Type','Jadwal','Kode Warna','Warna','Batch No','Basis (KG)','CM (KG)','Kemasan Summary','Canning (KG)','Yield (%)','Berat Awal','Berat Akhir','Selisih','Op. Canning','Op. Packing','Notes']);
            foreach ($data as $r) {
                fputcsv($file, [$r['doc_id'],$r['prod_date'],$r['shift'],$r['type'],$r['schedule_category'],$r['kode_warna'],$r['warna'],$r['batch_no'],$r['basis_kg'],$r['realisasi_cm_kg'],$r['detail_kemasan_summary'],$r['realisasi_canning_kg'],$r['yield_percent'],$r['berat_awal'],$r['berat_akhir'],$r['selisih_kg'],$r['operator_canning'],$r['operator_packing'],$r['notes']]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}