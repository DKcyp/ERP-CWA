<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ProductStockQuickViewController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('material-stock');
        View::share('activeMenu', 'material-management');
    }

    public function index()
    {
        return view('material-management.product-stock-quick-view');
    }

    public function data(Request $request)
    {
        $data = $this->store->all();

        $result = array_map(function ($i) use ($data) {
            return [
                'product_id' => $i['product_id'] ?? '',
                'name' => $i['name'] ?? '',
                'warehouse' => $i['warehouse'] ?? '',
                'available' => ($i['current_stock'] ?? 0) - ($i['reserved_stock'] ?? 0),
                'uom' => $i['uom'] ?? '',
                'last_updated' => $i['updated_at'] ?? $i['created_at'] ?? date('Y-m-d H:i:s'),
            ];
        }, $data);

        if ($request->filled('search')) {
            $q = strtolower($request->search);
            $result = array_values(array_filter($result, fn($i) =>
                stripos($i['product_id'], $q) !== false ||
                stripos($i['name'], $q) !== false ||
                stripos($i['warehouse'], $q) !== false
            ));
        }

        $result = array_values($result);
        usort($result, fn($a, $b) => strcasecmp($a['name'], $b['name']) ?: strcasecmp($a['warehouse'], $b['warehouse']));

        return response()->json(['data' => $result, 'count' => count($result)]);
    }
}
