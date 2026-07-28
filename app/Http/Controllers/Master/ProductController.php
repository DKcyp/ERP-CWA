<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ProductController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('product');
        View::share('activeMenu', 'product');
    }

    public function index()
    {
        return view('master.product.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['product_id'] ?? '', $q) !== false ||
                stripos($i['barcode'] ?? '', $q) !== false ||
                stripos($i['supplier'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_brand')) {
            $data = array_filter($data, fn($i) => ($i['brand'] ?? '') === $request->filter_brand);
        }
        if ($request->filled('filter_group')) {
            $data = array_filter($data, fn($i) => ($i['group'] ?? '') === $request->filter_group);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('stock_fmt', fn($r) => number_format((int)($r['stock'] ?? 0), 0, ',', '.'))
            ->addColumn('tonase_fmt', fn($r) => number_format((int)($r['tonase'] ?? 0), 0, ',', '.'))
            ->addColumn('kg_fmt', fn($r) => number_format((int)($r['kg'] ?? 0), 0, ',', '.'))
            ->addColumn('price_fmt', fn($r) => 'Rp ' . number_format((int)($r['def_sales_price'] ?? 0), 0, ',', '.'))
            ->addColumn('active_badge', fn($r) => ($r['active'] ?? 'Y') === 'Y'
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-secondary">Inactive</span>')
            ->addColumn('action', fn($row) => '<div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-primary btn-edit" data-id="'.$row['id'].'"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-outline-danger btn-delete" data-id="'.$row['id'].'"><i class="bi bi-trash"></i></button>
            </div>')
            ->rawColumns(['active_badge','action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'      => ['required','string','max:50'],
            'name'            => ['required','string','max:200'],
            'stock'           => ['required','numeric','min:0'],
            'uom'             => ['nullable','string','max:20'],
            'tonase'          => ['nullable','numeric','min:0'],
            'kg'              => ['nullable','numeric','min:0'],
            'def_sales_price' => ['required','numeric','min:0'],
            'supplier'        => ['nullable','string','max:200'],
            'barcode'         => ['nullable','string','max:50'],
            'location'        => ['nullable','string','max:100'],
            'type'            => ['nullable','string','max:50'],
            'brand'           => ['nullable','string','max:100'],
            'group'           => ['nullable','string','max:100'],
            'category'        => ['nullable','string','max:100'],
            'series'          => ['nullable','string','max:100'],
            'quality'         => ['nullable','string','max:50'],
            'active'          => ['required','string','in:Y,N'],
        ]);
        $this->store->create($request->only('product_id','name','stock','uom','tonase','kg','def_sales_price','supplier','barcode','location','type','brand','group','category','series','quality','active'));
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    public function show($id)
    {
        $d = $this->store->find($id);
        return $d ? response()->json(['success'=>true,'data'=>$d]) : response()->json(['message'=>'Data tidak ditemukan.'],404);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id'      => ['required','string','max:50'],
            'name'            => ['required','string','max:200'],
            'stock'           => ['required','numeric','min:0'],
            'uom'             => ['nullable','string','max:20'],
            'tonase'          => ['nullable','numeric','min:0'],
            'kg'              => ['nullable','numeric','min:0'],
            'def_sales_price' => ['required','numeric','min:0'],
            'supplier'        => ['nullable','string','max:200'],
            'barcode'         => ['nullable','string','max:50'],
            'location'        => ['nullable','string','max:100'],
            'type'            => ['nullable','string','max:50'],
            'brand'           => ['nullable','string','max:100'],
            'group'           => ['nullable','string','max:100'],
            'category'        => ['nullable','string','max:100'],
            'series'          => ['nullable','string','max:100'],
            'quality'         => ['nullable','string','max:50'],
            'active'          => ['required','string','in:Y,N'],
        ]);
        $this->store->update($id, $request->only('product_id','name','stock','uom','tonase','kg','def_sales_price','supplier','barcode','location','type','brand','group','category','series','quality','active'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}