<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class CustomerMasterController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('customers');
        View::share('activeMenu', 'customer-master');
    }

    public function index()
    {
        return view('master.customer-master.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_channel')) {
            $v = $request->filter_channel;
            if ($v !== 'all') $data = array_filter($data, fn($i) => ($i['channel_outlet'] ?? '') === $v);
        }

        if ($request->has('filter_active') && $request->filter_active !== '' && $request->filter_active !== 'all') {
            $v = (bool) $request->filter_active;
            $data = array_filter($data, fn($i) => ($i['active'] ?? false) === $v);
        }

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['nik'] ?? '', $q) !== false ||
                stripos($i['city'] ?? '', $q) !== false ||
                stripos($i['phone'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('credit_limit_fmt', function ($row) {
                return 'Rp ' . number_format((int)($row['credit_limit'] ?? 0), 0, ',', '.');
            })
            ->addColumn('active_badge', function ($row) {
                $a = $row['active'] ?? false;
                return '<span class="badge ' . ($a ? 'bg-success' : 'bg-secondary') . '">' . ($a ? 'Aktif' : 'Non-Aktif') . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $row['id'] . '"><i class="bi bi-pencil"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['active_badge', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => ['required', 'string', 'max:200'],
            'nik'             => ['nullable', 'string', 'max:30'],
            'nik_name'        => ['nullable', 'string', 'max:200'],
            'npwp'            => ['nullable', 'string', 'max:30'],
            'sim'             => ['nullable', 'string', 'max:30'],
            'marketing'       => ['nullable', 'string', 'max:100'],
            'credit_limit'    => ['nullable', 'integer', 'min:0'],
            'due_date_warning'=> ['nullable', 'integer', 'min:0'],
            'warehouse'       => ['nullable', 'string', 'max:100'],
            'active'          => ['nullable'],
            'contact'         => ['nullable', 'string', 'max:200'],
            'position'        => ['nullable', 'string', 'max:100'],
            'address1'        => ['nullable', 'string', 'max:255'],
            'address2'        => ['nullable', 'string', 'max:255'],
            'kecamatan'       => ['nullable', 'string', 'max:100'],
            'kabupaten'       => ['nullable', 'string', 'max:100'],
            'city'            => ['nullable', 'string', 'max:100'],
            'zip'             => ['nullable', 'string', 'max:10'],
            'channel_outlet'  => ['nullable', 'string', 'max:100'],
            'rayon_sales'     => ['nullable', 'string', 'max:100'],
            'province'        => ['nullable', 'string', 'max:100'],
            'country'         => ['nullable', 'string', 'max:100'],
            'phone'           => ['nullable', 'string', 'max:30'],
            'mobile_phone'    => ['nullable', 'string', 'max:30'],
            'email'           => ['nullable', 'email', 'max:100'],
            'note'            => ['nullable', 'string'],
            'price_list_id'   => ['nullable', 'string', 'max:50'],
            'term'            => ['nullable', 'integer', 'min:0'],
        ]);

        $payload = $request->except(['_token', 'active']);
        $payload['active'] = $request->boolean('active', true);
        $payload['credit_limit'] = (int)($payload['credit_limit'] ?? 0);
        $payload['due_date_warning'] = (int)($payload['due_date_warning'] ?? 0);
        $payload['term'] = (int)($payload['term'] ?? 0);

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
            'name'            => ['required', 'string', 'max:200'],
            'nik'             => ['nullable', 'string', 'max:30'],
            'nik_name'        => ['nullable', 'string', 'max:200'],
            'npwp'            => ['nullable', 'string', 'max:30'],
            'sim'             => ['nullable', 'string', 'max:30'],
            'marketing'       => ['nullable', 'string', 'max:100'],
            'credit_limit'    => ['nullable', 'integer', 'min:0'],
            'due_date_warning'=> ['nullable', 'integer', 'min:0'],
            'warehouse'       => ['nullable', 'string', 'max:100'],
            'active'          => ['nullable'],
            'contact'         => ['nullable', 'string', 'max:200'],
            'position'        => ['nullable', 'string', 'max:100'],
            'address1'        => ['nullable', 'string', 'max:255'],
            'address2'        => ['nullable', 'string', 'max:255'],
            'kecamatan'       => ['nullable', 'string', 'max:100'],
            'kabupaten'       => ['nullable', 'string', 'max:100'],
            'city'            => ['nullable', 'string', 'max:100'],
            'zip'             => ['nullable', 'string', 'max:10'],
            'channel_outlet'  => ['nullable', 'string', 'max:100'],
            'rayon_sales'     => ['nullable', 'string', 'max:100'],
            'province'        => ['nullable', 'string', 'max:100'],
            'country'         => ['nullable', 'string', 'max:100'],
            'phone'           => ['nullable', 'string', 'max:30'],
            'mobile_phone'    => ['nullable', 'string', 'max:30'],
            'email'           => ['nullable', 'email', 'max:100'],
            'note'            => ['nullable', 'string'],
            'price_list_id'   => ['nullable', 'string', 'max:50'],
            'term'            => ['nullable', 'integer', 'min:0'],
        ]);

        $payload = $request->except(['_token', 'active']);
        $payload['active'] = $request->boolean('active', true);
        $payload['credit_limit'] = (int)($payload['credit_limit'] ?? 0);
        $payload['due_date_warning'] = (int)($payload['due_date_warning'] ?? 0);
        $payload['term'] = (int)($payload['term'] ?? 0);

        $this->store->update($id, $payload);

        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}
