<?php

namespace App\Http\Controllers\TransitArea;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SalesmanArListPmbController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('salesman_ar_list_pmb');
        View::share('activeMenu', 'salesman-ar-list-pmb');
    }

    public function index()
    {
        return view('transit-area.salesman-ar-list-pmb.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['salesman'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('collection_53_90_fmt', fn($r) => 'Rp ' . number_format((int)($r['collection_53_90'] ?? 0), 0, ',', '.'))
            ->addColumn('collection_gt90_fmt', fn($r) => 'Rp ' . number_format((int)($r['collection_gt90'] ?? 0), 0, ',', '.'))
            ->addColumn('total_collection_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_collection'] ?? 0), 0, ',', '.'))
            ->addColumn('ach_0_52_fmt', fn($r) => 'Rp ' . number_format((int)($r['ach_0_52'] ?? 0), 0, ',', '.'))
            ->addColumn('ach_53_90_fmt', fn($r) => 'Rp ' . number_format((int)($r['ach_53_90'] ?? 0), 0, ',', '.'))
            ->addColumn('ach_gt90_fmt', fn($r) => 'Rp ' . number_format((int)($r['ach_gt90'] ?? 0), 0, ',', '.'))
            ->addColumn('total_ach_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_ach'] ?? 0), 0, ',', '.'))
            ->addColumn('percentage_fmt', fn($r) => number_format((float)($r['percentage'] ?? 0), 2) . ' %')
            ->make(true);
    }

    public function show($id)
    {
        $d = $this->store->find($id);
        return $d ? response()->json(['success' => true, 'data' => $d]) : response()->json(['message' => 'Data tidak ditemukan.'], 404);
    }
}