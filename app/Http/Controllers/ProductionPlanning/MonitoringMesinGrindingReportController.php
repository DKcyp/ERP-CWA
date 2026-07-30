<?php

namespace App\Http\Controllers\ProductionPlanning;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class MonitoringMesinGrindingReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('monitoring-mesin-grinding-report');
        $this->initDummyData();
        View::share('activeMenu', 'monitoring-mesin-grinding-report');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $products = ['Wall Paint White 20L','Wall Paint Cream 10L','Primer Grey 5L','Top Coat Clear 15L','Cat Ekonomis 5L','Pigment Red Oxide','Pigment Yellow Ochre'];
        $machines = ['GR-01','GR-02','GR-03'];
        $operators = ['Budi Santoso','Andi Kurniawan','Citra Dewi','Dedi Kuswanto','Eka Putri','Fajar Nugroho'];
        $types = ['Water Based','Solvent Based'];
        $shifts = ['Shift 1','Shift 2','Shift 3'];

        $data = [];
        for ($d = 0; $d < 5; $d++) {
            $date = date('Y-m-d', strtotime("2026-07-26 +{$d} days"));
            for ($r = 0; $r < rand(2, 5); $r++) {
                $tonase = round(rand(500, 2000) / 100, 2);
                $startH = rand(6, 18);
                $jamObs = round(rand(10, 45) / 10, 1);
                $micron = round(rand(5, 50) / 10, 1);
                $micronBadge = $micron <= 15 ? 'bg-success' : ($micron <= 30 ? 'bg-warning text-dark' : 'bg-danger');

                $data[] = [
                    'doc_id' => 'MGR-'.date('Ymd', strtotime($date)).'-'.str_pad($d * 5 + $r + 1, 3, '0', STR_PAD_LEFT),
                    'date' => $date, 'prod_date' => $date,
                    'shift' => $shifts[array_rand($shifts)],
                    'machine' => $machines[array_rand($machines)],
                    'type' => $types[array_rand($types)],
                    'nama_product' => $products[array_rand($products)],
                    'batch_no' => 'GR-ADN-'.str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT),
                    'tonase' => $tonase,
                    'no_mesin' => $machines[array_rand($machines)],
                    'mulai' => sprintf('%02d:%02d', $startH, rand(0, 29)),
                    'finish' => sprintf('%02d:%02d', $startH + rand(2, 4), rand(0, 59)),
                    'siklus_ke' => rand(1, 4),
                    'jam_pengamatan' => $jamObs,
                    'speed_gear_pump' => rand(800, 1500),
                    'speed_blade' => rand(1000, 2500),
                    'hasil_micron' => $micron,
                    'micron_badge' => $micronBadge,
                    'operator' => $operators[array_rand($operators)],
                    'notes' => $micron > 35 ? 'Kehalusan di atas batas' : '',
                ];
            }
        }
        foreach ($data as $item) { $this->store->create($item); }
    }

    public function index()
    {
        return view('production-planning.monitoring-mesin-grinding-report.index');
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
        if ($request->filled('filter_date_from')) $data = array_filter($data, fn($i) => ($i['prod_date'] ?? '') >= $request->filter_date_from);
        if ($request->filled('filter_date_to')) $data = array_filter($data, fn($i) => ($i['prod_date'] ?? '') <= $request->filter_date_to);
        if ($request->filled('filter_shift') && $request->filter_shift !== 'all') $data = array_filter($data, fn($i) => ($i['shift'] ?? '') === $request->filter_shift);
        if ($request->filled('filter_machine') && $request->filter_machine !== 'all') $data = array_filter($data, fn($i) => ($i['machine'] ?? '') === $request->filter_machine);
        if ($request->filled('filter_type') && $request->filter_type !== 'all') $data = array_filter($data, fn($i) => ($i['type'] ?? '') === $request->filter_type);
        if ($request->filled('filter_operator') && $request->filter_operator !== 'all') $data = array_filter($data, fn($i) => ($i['operator'] ?? '') === $request->filter_operator);

        $data = array_values($data);

        $totalTon = array_sum(array_column($data, 'tonase'));
        $avgMicron = count($data) > 0 ? round(array_sum(array_column($data, 'hasil_micron')) / count($data), 1) : 0;
        $avgGear = count($data) > 0 ? round(array_sum(array_column($data, 'speed_gear_pump')) / count($data)) : 0;
        $avgBlade = count($data) > 0 ? round(array_sum(array_column($data, 'speed_blade')) / count($data)) : 0;
        $totalSiklus = array_sum(array_column($data, 'siklus_ke'));

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('prod_date_fmt', fn($r) => \Carbon\Carbon::parse($r['prod_date'])->format('d/m/Y'))
            ->addColumn('tonase_fmt', fn($r) => number_format($r['tonase'], 2, ',', '.'))
            ->addColumn('jam', fn($r) => $r['mulai'].' - '.$r['finish'])
            ->addColumn('micron_badge', function ($r) {
                $m = $r['hasil_micron'];
                if ($m <= 15) return '<span class="badge bg-success">'.$m.' u</span>';
                if ($m <= 30) return '<span class="badge bg-warning text-dark">'.$m.' u</span>';
                return '<span class="badge bg-danger">'.$m.' u</span>';
            })
            ->rawColumns(['tonase_fmt','jam','micron_badge'])
            ->with(['summary' => [
                'total_ton' => $totalTon, 'avg_micron' => $avgMicron,
                'avg_gear' => $avgGear, 'avg_blade' => $avgBlade,
                'total_siklus' => $totalSiklus, 'total_records' => count($data),
            ]])
            ->make(true);
    }

    public function export(Request $request)
    {
        $data = $this->store->all();
        $filename = 'monitoring-mesin-grinding-report-'.date('Y-m-d').'.csv';
        $headers = ['Content-Type: text/csv','Content-Disposition: attachment;filename="'.$filename.'"'];
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Doc ID','Prod Date','Shift','Machine','Type','Product','Batch No','Tonase','No Mesin','Mulai','Finish','Siklus Ke','Jam Pengamatan','Gear Pump (RPM)','Blade (RPM)','Micron (u)','Operator','Notes']);
            foreach ($data as $r) {
                fputcsv($file, [$r['doc_id'],$r['prod_date'],$r['shift'],$r['machine'],$r['type'],$r['nama_product'],$r['batch_no'],$r['tonase'],$r['no_mesin'],$r['mulai'],$r['finish'],$r['siklus_ke'],$r['jam_pengamatan'],$r['speed_gear_pump'],$r['speed_blade'],$r['hasil_micron'],$r['operator'],$r['notes']]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}