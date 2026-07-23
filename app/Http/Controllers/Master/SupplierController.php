<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    protected DummyStore $supplierStore;
    protected DummyStore $groupStore;
    protected DummyStore $centerStore;

    public function __construct()
    {
        $this->supplierStore = new DummyStore('suppliers');
        $this->groupStore = new DummyStore('supplier-groups');
        $this->centerStore = new DummyStore('supplier-centers');
    }

    public function index(): View
    {
        return view('master.supplier.index');
    }

    public function table(Request $request)
    {
        $data = $this->supplierStore->all();

        if ($request->filled('filter_group')) {
            $data = array_filter($data, fn($i) => ($i['supplier_group_id'] ?? '') === $request->filter_group);
        }
        if ($request->filled('filter_center')) {
            $data = array_filter($data, fn($i) => ($i['supplier_center_id'] ?? '') === $request->filter_center);
        }
        if ($request->has('filter_status') && $request->filter_status !== '') {
            $status = (bool) $request->filter_status;
            $data = array_filter($data, fn($i) => ($i['status'] ?? false) === $status);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('group_name', fn($row) => $row['supplier_group_name'] ?? '-')
            ->addColumn('center_name', fn($row) => $row['supplier_center_name'] ?? '-')
            ->addColumn('status_badge', function ($row) {
                $active = $row['status'] ?? false;
                $class  = $active ? 'bg-success' : 'bg-secondary';
                $label  = $active ? 'Aktif' : 'Non-Aktif';
                return '<span class="badge ' . $class . '">' . $label . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $row['id'] . '"><i class="bi bi-pencil"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['action', 'status_badge'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_code'     => ['required', 'string', 'max:50'],
            'name'              => ['required', 'string', 'max:150'],
            'supplier_group_id' => ['nullable', 'string'],
            'supplier_center_id'=> ['nullable', 'string'],
            'phone'             => ['nullable', 'string', 'max:30'],
            'email'             => ['nullable', 'email', 'max:100'],
            'address'           => ['nullable', 'string'],
            'term_of_payment'   => ['nullable', 'integer', 'min:0'],
            'status'            => ['nullable'],
        ]);

        $payload = $request->only([
            'supplier_code', 'name', 'supplier_group_id', 'supplier_center_id',
            'phone', 'email', 'address', 'term_of_payment',
        ]);
        $payload['status'] = $request->boolean('status', true);
        $payload['supplier_group_name'] = $this->groupStore->find($payload['supplier_group_id'] ?? '')['name'] ?? '';
        $payload['supplier_center_name'] = $this->centerStore->find($payload['supplier_center_id'] ?? '')['name'] ?? '';

        $this->supplierStore->create($payload);

        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    public function show($id)
    {
        $data = $this->supplierStore->find($id);

        $data['supplier_group'] = $this->groupStore->find($data['supplier_group_id'] ?? '');
        $data['supplier_center'] = $this->centerStore->find($data['supplier_center_id'] ?? '');

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_code'     => ['required', 'string', 'max:50'],
            'name'              => ['required', 'string', 'max:150'],
            'supplier_group_id' => ['nullable', 'string'],
            'supplier_center_id'=> ['nullable', 'string'],
            'phone'             => ['nullable', 'string', 'max:30'],
            'email'             => ['nullable', 'email', 'max:100'],
            'address'           => ['nullable', 'string'],
            'term_of_payment'   => ['nullable', 'integer', 'min:0'],
            'status'            => ['nullable'],
        ]);

        $payload = $request->only([
            'supplier_code', 'name', 'supplier_group_id', 'supplier_center_id',
            'phone', 'email', 'address', 'term_of_payment',
        ]);
        $payload['status'] = $request->boolean('status', true);
        $payload['supplier_group_name'] = $this->groupStore->find($payload['supplier_group_id'] ?? '')['name'] ?? '';
        $payload['supplier_center_name'] = $this->centerStore->find($payload['supplier_center_id'] ?? '')['name'] ?? '';

        $this->supplierStore->update($id, $payload);

        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->supplierStore->delete($id);

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }

    public function getSupplierGroups(Request $request)
    {
        $q = (string) $request->q;
        $data = $this->groupStore->all();
        $filtered = array_filter($data, fn($i) => $q === '' || stripos($i['name'] ?? '', $q) !== false);

        $result = array_map(fn($i) => ['id' => $i['id'], 'text' => $i['name'] ?? ''], $filtered);

        return response()->json(['success' => true, 'data' => array_values($result)]);
    }

    public function getSupplierCenters(Request $request)
    {
        $q = (string) $request->q;
        $data = $this->centerStore->all();
        $filtered = array_filter($data, fn($i) => $q === '' || stripos($i['name'] ?? '', $q) !== false);

        $result = array_map(fn($i) => ['id' => $i['id'], 'text' => $i['name'] ?? ''], $filtered);

        return response()->json(['success' => true, 'data' => array_values($result)]);
    }
}
