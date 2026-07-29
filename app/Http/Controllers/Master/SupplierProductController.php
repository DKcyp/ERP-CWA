<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SupplierProductController extends Controller
{
    protected DummyStore $store;
    protected DummyStore $productStore;
    protected DummyStore $supplierStore;

    public function __construct()
    {
        $this->store = new DummyStore('supplier-product');
        $this->productStore = new DummyStore('product');
        $this->supplierStore = new DummyStore('suppliers');
        View::share('activeMenu', 'supplier-product');
    }

    public function index()
    {
        $products = $this->productStore->all();
        $suppliers = $this->supplierStore->all();
        return view('master.supplier-product.index', compact('products', 'suppliers'));
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['supplier'] ?? '', $q) !== false ||
                stripos($i['product'] ?? '', $q) !== false
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
            'supplier'            => ['required', 'string', 'max:200'],
            'product'             => ['required', 'string', 'max:200'],
            'supplier_item_code'  => ['nullable', 'string', 'max:50'],
            'supplier_item_name'  => ['nullable', 'string', 'max:200'],
            'lead_time_days'      => ['nullable', 'integer', 'min:0'],
            'active'              => ['required', 'string', 'in:Y,N'],
        ]);
        $this->store->create($request->only('supplier', 'product', 'supplier_item_code', 'supplier_item_name', 'lead_time_days', 'active'));
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
            'supplier'            => ['required', 'string', 'max:200'],
            'product'             => ['required', 'string', 'max:200'],
            'supplier_item_code'  => ['nullable', 'string', 'max:50'],
            'supplier_item_name'  => ['nullable', 'string', 'max:200'],
            'lead_time_days'      => ['nullable', 'integer', 'min:0'],
            'active'              => ['required', 'string', 'in:Y,N'],
        ]);
        $this->store->update($id, $request->only('supplier', 'product', 'supplier_item_code', 'supplier_item_name', 'lead_time_days', 'active'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}