<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class EmployeeController extends Controller
{
    protected DummyStore $store;
    protected DummyStore $userStore;
    protected DummyStore $commissionStore;
    protected DummyStore $warehouseStore;

    public function __construct()
    {
        $this->store = new DummyStore('employee');
        $this->userStore = new DummyStore('users');
        $this->commissionStore = new DummyStore('commission');
        $this->warehouseStore = new DummyStore('warehouse');
        View::share('activeMenu', 'employee');
    }

    public function index()
    {
        $users = $this->userStore->all();
        $commissions = $this->commissionStore->all();
        $warehouses = $this->warehouseStore->all();
        return view('master.employee.index', compact('users', 'commissions', 'warehouses'));
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['user_id'] ?? '', $q) !== false ||
                stripos($i['transit_area'] ?? '', $q) !== false
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
            'employee_id'   => ['required','string','max:50'],
            'name'          => ['required','string','max:200'],
            'user_id'       => ['required','string','max:50'],
            'commission_id' => ['nullable','string','max:50'],
            'active'        => ['required','string','in:Y,N'],
            'transit_area'  => ['nullable','string','max:200'],
        ]);
        $this->store->create($request->only('employee_id','name','user_id','commission_id','active','transit_area'));
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
            'employee_id'   => ['required','string','max:50'],
            'name'          => ['required','string','max:200'],
            'user_id'       => ['required','string','max:50'],
            'commission_id' => ['nullable','string','max:50'],
            'active'        => ['required','string','in:Y,N'],
            'transit_area'  => ['nullable','string','max:200'],
        ]);
        $this->store->update($id, $request->only('employee_id','name','user_id','commission_id','active','transit_area'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}