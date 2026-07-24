<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class CategoryExceptionController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('category-exceptions');
        View::share('activeMenu', 'category-exception');
    }

    public function index()
    {
        return view('Sales-distribution.category-exception.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();
        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) => stripos($i['category'] ?? '', $q) !== false);
        }
        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('action', fn($r) => '<div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $r['id'] . '"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $r['id'] . '"><i class="bi bi-trash"></i></button>
            </div>')
            ->rawColumns(['action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate(['category' => ['required', 'string', 'max:200']]);
        $this->store->create($request->only('category'));
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    public function show($id)
    {
        $d = $this->store->find($id);
        return $d ? response()->json(['success'=>true,'data'=>$d]) : response()->json(['message'=>'Data tidak ditemukan.'],404);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['category' => ['required', 'string', 'max:200']]);
        $this->store->update($id, $request->only('category'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}
