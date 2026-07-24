<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class CustomerCentreController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('customer-centres');
        View::share('activeMenu', 'customer-centre');
    }

    public function index()
    {
        return view('master.customer-centre.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();
        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['centre_name'] ?? '', $q) !== false ||
                stripos($i['centre_code'] ?? '', $q) !== false ||
                stripos($i['pic_name'] ?? '', $q) !== false
            );
        }
        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('action', fn($row) => '<div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $row['id'] . '"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>
            </div>')
            ->rawColumns(['action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'  => ['required', 'string', 'max:50'],
            'centre_code'  => ['required', 'string', 'max:50'],
            'centre_name'  => ['required', 'string', 'max:200'],
            'address'      => ['nullable', 'string'],
            'pic_name'     => ['nullable', 'string', 'max:100'],
            'phone'        => ['nullable', 'string', 'max:30'],
            'email'        => ['nullable', 'email', 'max:100'],
            'warehouse_id' => ['nullable', 'string', 'max:50'],
        ]);
        $this->store->create($request->only('customer_id', 'centre_code', 'centre_name', 'address', 'pic_name', 'phone', 'email', 'warehouse_id'));
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    public function show($id)
    {
        $data = $this->store->find($id);
        if (!$data) return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id'  => ['required', 'string', 'max:50'],
            'centre_code'  => ['required', 'string', 'max:50'],
            'centre_name'  => ['required', 'string', 'max:200'],
            'address'      => ['nullable', 'string'],
            'pic_name'     => ['nullable', 'string', 'max:100'],
            'phone'        => ['nullable', 'string', 'max:30'],
            'email'        => ['nullable', 'email', 'max:100'],
            'warehouse_id' => ['nullable', 'string', 'max:50'],
        ]);
        $this->store->update($id, $request->only('customer_id', 'centre_code', 'centre_name', 'address', 'pic_name', 'phone', 'email', 'warehouse_id'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}
