<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class HierarchyController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('hierarchy');
        View::share('activeMenu', 'hierarchy');
    }

    public function index()
    {
        return view('master.hierarchy.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['code'] ?? '', $q) !== false ||
                stripos($i['level'] ?? '', $q) !== false
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
            ->rawColumns(['active_badge','action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'level'    => ['required','string','max:50'],
            'parent_hierarchy' => ['nullable','string','max:100'],
            'code'     => ['required','string','max:50'],
            'name'     => ['required','string','max:200'],
            'active'   => ['required','string','in:Y,N'],
        ]);
        $this->store->create(
            $request->only('level','parent_hierarchy','code','name','active')
        );
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
            'level'    => ['required','string','max:50'],
            'parent_hierarchy' => ['nullable','string','max:100'],
            'code'     => ['required','string','max:50'],
            'name'     => ['required','string','max:200'],
            'active'   => ['required','string','in:Y,N'],
        ]);
        $this->store->update($id,
            $request->only('level','parent_hierarchy','code','name','active')
        );
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}