<?php

namespace App\Http\Controllers\TransitArea;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class UbmDailyControlProgressSalesReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('ubm_daily_control_progress_sales_report');
        View::share('activeMenu', 'ubm-daily-control-progress-sales-report');
    }

    public function index()
    {
        return view('transit-area.ubm-daily-control-progress-sales-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) => stripos($i['transit_area'] ?? '', $q) !== false);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('target_bulanan_fmt', fn($r) => 'Rp ' . number_format((int)($r['target_bulanan'] ?? 0), 0, ',', '.'))
            ->addColumn('toleransi_fmt', fn($r) => 'Rp ' . number_format((int)($r['toleransi'] ?? 0), 0, ',', '.'))
            ->addColumn('belum_tercapai_fmt', fn($r) => 'Rp ' . number_format((int)($r['belum_tercapai'] ?? 0), 0, ',', '.'))
            ->addColumn('tahun_lalu_fmt', fn($r) => 'Rp ' . number_format((int)($r['tahun_lalu'] ?? 0), 0, ',', '.'))
            ->addColumn('bulan_lalu_fmt', fn($r) => 'Rp ' . number_format((int)($r['bulan_lalu'] ?? 0), 0, ',', '.'))
            ->addColumn('pencapaian_ta_fmt', fn($r) => 'Rp ' . number_format((int)($r['pencapaian_ta'] ?? 0), 0, ',', '.'))
            ->addColumn('target_hari_ini_fmt', fn($r) => 'Rp ' . number_format((int)($r['target_hari_ini'] ?? 0), 0, ',', '.'))
            ->addColumn('akumulasi_fmt', fn($r) => 'Rp ' . number_format((int)($r['akumulasi'] ?? 0), 0, ',', '.'))
            ->addColumn('persen_target_fmt', fn($r) => number_format((float)($r['persen_target'] ?? 0), 2) . ' %')
            ->addColumn('persen_target_tlr_fmt', fn($r) => number_format((float)($r['persen_target_tlr'] ?? 0), 2) . ' %')
            ->make(true);
    }

    public function show($id)
    {
        $d = $this->store->find($id);
        return $d ? response()->json(['success' => true, 'data' => $d]) : response()->json(['message' => 'Data tidak ditemukan.'], 404);
    }
}