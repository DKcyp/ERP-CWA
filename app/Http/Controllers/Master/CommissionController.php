<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class CommissionController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('commission');
        View::share('activeMenu', 'commission');
    }

    public function index()
    {
        return view('master.commission.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['target_type'] ?? '', $q) !== false
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
            'name'          => ['required','string','max:200'],
            'target_type'   => ['required','string','max:50'],
            'min_achieve'   => ['nullable','numeric','min:0'],
            'max_achieve'   => ['nullable','numeric','min:0'],
            'rate_percent'  => ['required','numeric','min:0','max:100'],
            'active'        => ['required','string','in:Y,N'],
        ]);
        $this->store->create($request->only('name','target_type','min_achieve','max_achieve','rate_percent','active'));
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
            'name'          => ['required','string','max:200'],
            'target_type'   => ['required','string','max:50'],
            'min_achieve'   => ['nullable','numeric','min:0'],
            'max_achieve'   => ['nullable','numeric','min:0'],
            'rate_percent'  => ['required','numeric','min:0','max:100'],
            'active'        => ['required','string','in:Y,N'],
        ]);
        $this->store->update($id, $request->only('name','target_type','min_achieve','max_achieve','rate_percent','active'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}