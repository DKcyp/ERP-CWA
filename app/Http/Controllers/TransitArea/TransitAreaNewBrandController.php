<?php

namespace App\Http\Controllers\TransitArea;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class TransitAreaNewBrandController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('transit_area_new_brand');
        View::share('activeMenu', 'transit-area-new-brand');
    }

    public function index()
    {
        return view('transit-area.transit-area-new-brand.index');
    }

    public function table()
    {
        $data = $this->store->all();
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
        $request->validate(['brand' => ['required','string','max:100']]);
        $this->store->create($request->only('brand'));
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    public function show($id)
    {
        $d = $this->store->find($id);
        return $d ? response()->json(['success'=>true,'data'=>$d]) : response()->json(['message'=>'Data tidak ditemukan.'],404);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['brand' => ['required','string','max:100']]);
        $this->store->update($id, $request->only('brand'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}