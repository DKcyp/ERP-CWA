<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class PriceListController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('price-list');
        View::share('activeMenu', 'price-list');
    }

    public function index()
    {
        return view('master.price-list.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['code'] ?? '', $q) !== false ||
                stripos($i['currency'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('active_badge', fn($r) => ($r['active'] ?? 'Y') === 'Y'
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-secondary">Inactive</span>')
            ->addColumn('action', function ($row) {
                $id = $row['id'];
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-info btn-detail" data-id="'.$id.'" title="Detail"><i class="bi bi-eye"></i></button>
                    <button type="button" class="btn btn-outline-primary btn-edit" data-id="'.$id.'" title="Edit"><i class="bi bi-pencil"></i></button>
                    <button type="button" class="btn btn-outline-warning btn-duplicate" data-id="'.$id.'" title="Pembaruan"><i class="bi bi-copy"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-delete" data-id="'.$id.'" title="Hapus"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['active_badge', 'action'])->make(true);
    }

    public function detail($id)
    {
        $d = $this->store->find($id);
        if (!$d) return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        return response()->json(['success' => true, 'data' => $d]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'           => ['required', 'string', 'max:50'],
            'name'           => ['required', 'string', 'max:200'],
            'currency'       => ['required', 'string', 'max:10'],
            'effective_date' => ['required', 'date'],
            'expiry_date'    => ['nullable', 'date', 'after_or_equal:effective_date'],
            'active'         => ['required', 'string', 'in:Y,N'],
            'items'          => ['nullable', 'array'],
            'items.*.product_id'     => ['required', 'string', 'max:50'],
            'items.*.product_name'   => ['required', 'string', 'max:200'],
            'items.*.uom'            => ['required', 'string', 'max:20'],
            'items.*.price'          => ['required', 'numeric', 'min:0'],
            'items.*.min_qty'        => ['nullable', 'numeric', 'min:0'],
            'items.*.discount_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $payload = $request->only('code', 'name', 'currency', 'effective_date', 'expiry_date', 'active');
        $payload['items'] = $request->input('items', []);
        $this->store->create($payload);
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    public function show($id)
    {
        $d = $this->store->find($id);
        if (!$d) return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        return response()->json(['success' => true, 'data' => $d]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code'           => ['required', 'string', 'max:50'],
            'name'           => ['required', 'string', 'max:200'],
            'currency'       => ['required', 'string', 'max:10'],
            'effective_date' => ['required', 'date'],
            'expiry_date'    => ['nullable', 'date', 'after_or_equal:effective_date'],
            'active'         => ['required', 'string', 'in:Y,N'],
            'items'          => ['nullable', 'array'],
            'items.*.product_id'     => ['required', 'string', 'max:50'],
            'items.*.product_name'   => ['required', 'string', 'max:200'],
            'items.*.uom'            => ['required', 'string', 'max:20'],
            'items.*.price'          => ['required', 'numeric', 'min:0'],
            'items.*.min_qty'        => ['nullable', 'numeric', 'min:0'],
            'items.*.discount_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $payload = $request->only('code', 'name', 'currency', 'effective_date', 'expiry_date', 'active');
        $payload['items'] = $request->input('items', []);
        $this->store->update($id, $payload);
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function duplicate(Request $request)
    {
        $request->validate([
            'code'           => ['required', 'string', 'max:50'],
            'name'           => ['required', 'string', 'max:200'],
            'currency'       => ['required', 'string', 'max:10'],
            'effective_date' => ['required', 'date'],
            'expiry_date'    => ['nullable', 'date', 'after_or_equal:effective_date'],
            'active'         => ['required', 'string', 'in:Y,N'],
            'items'          => ['nullable', 'array'],
            'items.*.product_id'     => ['required', 'string', 'max:50'],
            'items.*.product_name'   => ['required', 'string', 'max:200'],
            'items.*.uom'            => ['required', 'string', 'max:20'],
            'items.*.price'          => ['required', 'numeric', 'min:0'],
            'items.*.min_qty'        => ['nullable', 'numeric', 'min:0'],
            'items.*.discount_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $payload = $request->only('code', 'name', 'currency', 'effective_date', 'expiry_date', 'active');
        $payload['items'] = $request->input('items', []);
        $this->store->create($payload);
        return response()->json(['message' => 'Pembaruan price list berhasil disimpan sebagai versi baru.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}