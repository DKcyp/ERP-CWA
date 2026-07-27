<?php

namespace App\Http\Controllers\TransitArea;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class UbmCollectionProgressReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('ubm_collection_progress_report');
        View::share('activeMenu', 'ubm-collection-progress-report');
    }

    public function index()
    {
        return view('transit-area.ubm-collection-progress-report.index');
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
            ->addColumn('collection_53_90_fmt', fn($r) => 'Rp ' . number_format((int)($r['collection_53_90'] ?? 0), 0, ',', '.'))
            ->addColumn('collection_gt90_fmt', fn($r) => 'Rp ' . number_format((int)($r['collection_gt90'] ?? 0), 0, ',', '.'))
            ->addColumn('total_collection_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_collection'] ?? 0), 0, ',', '.'))
            ->addColumn('uncollected_fmt', fn($r) => 'Rp ' . number_format((int)($r['uncollected'] ?? 0), 0, ',', '.'))
            ->addColumn('days_before_fmt', fn($r) => 'Rp ' . number_format((int)($r['days_before'] ?? 0), 0, ',', '.'))
            ->addColumn('target_fmt', fn($r) => 'Rp ' . number_format((int)($r['target'] ?? 0), 0, ',', '.'))
            ->addColumn('accumulation_fmt', fn($r) => 'Rp ' . number_format((int)($r['accumulation'] ?? 0), 0, ',', '.'))
            ->addColumn('collection_tertagih_fmt', fn($r) => number_format((float)($r['collection_tertagih'] ?? 0), 2) . ' %')
            ->make(true);
    }

    public function show($id)
    {
        $d = $this->store->find($id);
        return $d ? response()->json(['success' => true, 'data' => $d]) : response()->json(['message' => 'Data tidak ditemukan.'], 404);
    }
}