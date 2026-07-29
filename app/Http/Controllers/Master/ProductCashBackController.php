<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ProductCashBackController extends Controller
{
    protected DummyStore $store;
    protected DummyStore $productStore;
    protected DummyStore $supplierStore;

    public function __construct()
    {
        $this->store = new DummyStore('product-cash-back');
        $this->productStore = new DummyStore('product');
        $this->supplierStore = new DummyStore('suppliers');
        View::share('activeMenu', 'product-cash-back');
    }

    public function index()
    {
        $products = $this->productStore->all();
        $suppliers = $this->supplierStore->all();
        return view('master.product-cash-back.index', compact('products', 'suppliers'));
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['product'] ?? '', $q) !== false ||
                stripos($i['supplier'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('active_badge', fn($r) => ($r['active'] ?? 'Y') === 'Y'
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-secondary">Inactive</span>')
            ->addColumn('action', fn($row) => '<div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-primary btn-edit" data-id="'.$row['id'].'"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-outline-danger btn-delete" data-id="'.$row['id'].'"><i class="bi bi-trash"></i></button>
            </div>')
            ->rawColumns(['active_badge', 'action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => ['required', 'string', 'max:200'],
            'supplier'        => ['required', 'string', 'max:200'],
            'product'         => ['required', 'string', 'max:200'],
            'min_purchase'    => ['nullable', 'numeric', 'min:0'],
            'cashback_value'  => ['required', 'numeric', 'min:0'],
            'valid_from'      => ['required', 'date'],
            'valid_to'        => ['nullable', 'date', 'after_or_equal:valid_from'],
            'active'          => ['required', 'string', 'in:Y,N'],
        ]);
        $this->store->create($request->only('name', 'supplier', 'product', 'min_purchase', 'cashback_value', 'valid_from', 'valid_to', 'active'));
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    public function show($id)
    {
        $d = $this->store->find($id);
        return $d ? response()->json(['success'=>true, 'data'=>$d]) : response()->json(['message'=>'Data tidak ditemukan.'], 404);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'            => ['required', 'string', 'max:200'],
            'supplier'        => ['required', 'string', 'max:200'],
            'product'         => ['required', 'string', 'max:200'],
            'min_purchase'    => ['nullable', 'numeric', 'min:0'],
            'cashback_value'  => ['required', 'numeric', 'min:0'],
            'valid_from'      => ['required', 'date'],
            'valid_to'        => ['nullable', 'date', 'after_or_equal:valid_from'],
            'active'          => ['required', 'string', 'in:Y,N'],
        ]);
        $this->store->update($id, $request->only('name', 'supplier', 'product', 'min_purchase', 'cashback_value', 'valid_from', 'valid_to', 'active'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}