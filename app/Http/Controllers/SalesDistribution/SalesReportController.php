<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SalesReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('sales-reports');
        View::share('activeMenu', 'sales-report');
    }

    public function index()
    {
        $data = $this->store->all();
        $seriesList = collect($data)->pluck('series')->unique()->sort()->values()->all();
        $brandList = collect($data)->pluck('brand')->unique()->sort()->values()->all();
        return view('Sales-distribution.sales-report.index', compact('seriesList', 'brandList'));
    }

    public function table(Request $request)
    {
        $data = array_values($this->store->all());

        // Apply filters
        if ($request->filled('filter_date_start')) {
            $start = $request->filter_date_start;
            $data = array_filter($data, fn($i) => ($i['date'] ?? '') >= $start);
        }
        if ($request->filled('filter_date_end')) {
            $end = $request->filter_date_end;
            $data = array_filter($data, fn($i) => ($i['date'] ?? '') <= $end);
        }
        if ($request->filled('filter_series')) {
            $data = array_filter($data, fn($i) => ($i['series'] ?? '') === $request->filter_series);
        }
        if ($request->filled('filter_brand')) {
            $data = array_filter($data, fn($i) => ($i['brand'] ?? '') === $request->filter_brand);
        }
        if ($request->filled('filter_vat')) {
            $vat = $request->filter_vat === 'vat' ? true : false;
            $data = array_filter($data, fn($i) => ($i['is_vat'] ?? false) === $vat);
        }

        $groupBy = $request->get('group_by', 'customer');
        $grouped = collect($data)->groupBy(function ($i) use ($groupBy) {
            return match ($groupBy) {
                'customer' => $i['customer_id'] . '|' . $i['customer_name'],
                'product'  => $i['product_id'] . '|' . $i['product_name'],
                'supplier' => $i['supplier_id'] . '|' . $i['supplier_name'],
                'salesman' => $i['salesman_id'] . '|' . $i['salesman_name'],
                'category' => $i['category'] . '|' . $i['category'],
                default    => $i['customer_id'] . '|' . $i['customer_name'],
            };
        })->map(function ($items, $key) use ($groupBy) {
            [$id, $name] = explode('|', $key, 2);
            $totalQty = collect($items)->sum('qty');
            $totalAmt = collect($items)->sum(fn($i) => ($i['qty'] ?? 0) * ($i['price'] ?? 0));
            $base = ['id' => $id, 'name' => $name, 'total_qty' => $totalQty, 'total_amount' => $totalAmt];
            if ($groupBy === 'category') {
                $base['id'] = $name;
            }
            return $base;
        })->values();

        return DataTables::of($grouped)
            ->addIndexColumn()
            ->addColumn('total_qty_fmt', fn($r) => number_format((int)($r['total_qty'] ?? 0), 0, ',', '.'))
            ->addColumn('total_amount_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_amount'] ?? 0), 0, ',', '.'))
            ->make(true);
    }
}