<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class UomGeneralConvertionController extends Controller
{
    protected DummyStore $store;
    protected DummyStore $productStore;
    protected DummyStore $uomStore;

    public function __construct()
    {
        $this->store = new DummyStore('uom-general-convertion');
        $this->productStore = new DummyStore('product');
        $this->uomStore = new DummyStore('uom');
        View::share('activeMenu', 'uom-general-convertion');
    }

    public function index()
    {
        $products = $this->productStore->all();
        $uoms = $this->uomStore->all();
        return view('master.uom-general-convertion.index', compact('products', 'uoms'));
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['product'] ?? '', $q) !== false ||
                stripos($i['from_uom'] ?? '', $q) !== false ||
                stripos($i['to_uom'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('action', fn($row) => '<div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-primary btn-edit" data-id="'.$row['id'].'"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-outline-danger btn-delete" data-id="'.$row['id'].'"><i class="bi bi-trash"></i></button>
            </div>')
            ->rawColumns(['action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product'   => ['required', 'string', 'max:200'],
            'from_uom'  => ['required', 'string', 'max:20'],
            'to_uom'    => ['required', 'string', 'max:20'],
            'multiplier'=> ['required', 'numeric', 'gt:0'],
            'operator'  => ['nullable', 'string', 'max:10'],
        ]);
        $this->store->create($request->only('product', 'from_uom', 'to_uom', 'multiplier', 'operator'));
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
            'product'   => ['required', 'string', 'max:200'],
            'from_uom'  => ['required', 'string', 'max:20'],
            'to_uom'    => ['required', 'string', 'max:20'],
            'multiplier'=> ['required', 'numeric', 'gt:0'],
            'operator'  => ['nullable', 'string', 'max:10'],
        ]);
        $this->store->update($id, $request->only('product', 'from_uom', 'to_uom', 'multiplier', 'operator'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}