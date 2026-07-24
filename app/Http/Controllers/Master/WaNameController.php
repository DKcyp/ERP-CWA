<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class WaNameController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('wa-names');
        View::share('activeMenu', 'wa-name');
    }

    public function index()
    {
        return view('master.wa-name.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();
        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['phone_number'] ?? '', $q) !== false
            );
        }
        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('is_primary_badge', function ($row) {
                return $row['is_primary'] ? '<span class="badge bg-success">Primary</span>' : '<span class="badge bg-secondary">Secondary</span>';
            })
            ->addColumn('action', fn($row) => '<div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $row['id'] . '"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>
            </div>')
            ->rawColumns(['is_primary_badge', 'action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'   => ['required', 'string', 'max:50'],
            'name'          => ['required', 'string', 'max:200'],
            'phone_number'  => ['required', 'string', 'max:30'],
            'role_position' => ['nullable', 'string', 'max:100'],
            'is_primary'    => ['nullable'],
        ]);
        $payload = $request->only('customer_id', 'name', 'phone_number', 'role_position');
        $payload['is_primary'] = $request->boolean('is_primary', false);
        $this->store->create($payload);
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
            'customer_id'   => ['required', 'string', 'max:50'],
            'name'          => ['required', 'string', 'max:200'],
            'phone_number'  => ['required', 'string', 'max:30'],
            'role_position' => ['nullable', 'string', 'max:100'],
            'is_primary'    => ['nullable'],
        ]);
        $payload = $request->only('customer_id', 'name', 'phone_number', 'role_position');
        $payload['is_primary'] = $request->boolean('is_primary', false);
        $this->store->update($id, $payload);
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}
