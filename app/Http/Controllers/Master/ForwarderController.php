<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ForwarderController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('forwarder');
        View::share('activeMenu', 'forwarder');
    }

    public function index()
    {
        return view('master.forwarder.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['code'] ?? '', $q) !== false ||
                stripos($i['name'] ?? '', $q) !== false
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
            'code'            => ['required','string','max:50'],
            'name'            => ['required','string','max:200'],
            'contact_person'  => ['nullable','string','max:200'],
            'phone'           => ['nullable','string','max:50'],
            'email'           => ['nullable','email','max:200'],
            'address'         => ['nullable','string','max:500'],
            'active'          => ['required','string','in:Y,N'],
        ]);
        $this->store->create($request->only('code','name','contact_person','phone','email','address','active'));
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
            'code'            => ['required','string','max:50'],
            'name'            => ['required','string','max:200'],
            'contact_person'  => ['nullable','string','max:200'],
            'phone'           => ['nullable','string','max:50'],
            'email'           => ['nullable','email','max:200'],
            'address'         => ['nullable','string','max:500'],
            'active'          => ['required','string','in:Y,N'],
        ]);
        $this->store->update($id, $request->only('code','name','contact_person','phone','email','address','active'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}