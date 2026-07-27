<?php

namespace App\Http\Controllers\TransitArea;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class UbmNewProductSalesReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('ubm_new_product_sales_report');
        View::share('activeMenu', 'ubm-new-product-sales-report');
    }

    public function index()
    {
        return view('transit-area.ubm-new-product-sales-report.index');
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
            ->make(true);
    }

    public function show($id)
    {
        $d = $this->store->find($id);
        return $d ? response()->json(['success' => true, 'data' => $d]) : response()->json(['message' => 'Data tidak ditemukan.'], 404);
    }
}