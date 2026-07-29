<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class BankController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('bank');
        View::share('activeMenu', 'bank');
    }

    public function index()
    {
        return view('master.bank.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['bank_name'] ?? '', $q) !== false ||
                stripos($i['account_number'] ?? '', $q) !== false ||
                stripos($i['account_name'] ?? '', $q) !== false
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
            'bank_code'      => ['required','string','max:20'],
            'bank_name'      => ['required','string','max:100'],
            'branch'         => ['nullable','string','max:200'],
            'account_number' => ['required','string','max:50'],
            'account_name'   => ['required','string','max:200'],
            'active'         => ['required','string','in:Y,N'],
        ]);
        $this->store->create($request->only('bank_code','bank_name','branch','account_number','account_name','active'));
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
            'bank_code'      => ['required','string','max:20'],
            'bank_name'      => ['required','string','max:100'],
            'branch'         => ['nullable','string','max:200'],
            'account_number' => ['required','string','max:50'],
            'account_name'   => ['required','string','max:200'],
            'active'         => ['required','string','in:Y,N'],
        ]);
        $this->store->update($id, $request->only('bank_code','bank_name','branch','account_number','account_name','active'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}