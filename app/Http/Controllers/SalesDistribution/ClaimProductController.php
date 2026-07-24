<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ClaimProductController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('claim-products');
        View::share('activeMenu', 'claim-product');
    }

    public function index()
    {
        return view('Sales-distribution.claim-product.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();
        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['doc_id'] ?? '', $q) !== false ||
                stripos($i['customer_id'] ?? '', $q) !== false
            );
        }
        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => \Carbon\Carbon::parse($r['date'])->format('d/m/Y'))
            ->addColumn('total_point_fmt', fn($r) => number_format((int)($r['total_point_claim'] ?? 0), 0, ',', '.'))
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $row['id'] . '"><i class="bi bi-pencil"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'      => ['required', 'string', 'max:50'],
            'member_id'        => ['nullable', 'string', 'max:50'],
            'name'             => ['required', 'string', 'max:200'],
            'point_reguler'    => ['nullable', 'integer', 'min:0'],
            'point_promo'      => ['nullable', 'integer', 'min:0'],
            'point_type'       => ['nullable', 'string', 'max:50'],
            'doc_id'           => ['required', 'string', 'max:50'],
            'date'             => ['required', 'date'],
            'warehouse_id'     => ['nullable', 'string', 'max:50'],
            'user'             => ['nullable', 'string', 'max:100'],
            'type_name_id'     => ['nullable', 'string', 'max:50'],
            'note'             => ['nullable', 'string'],
            'total_point_claim'=> ['required', 'integer', 'min:0'],
            'items'            => ['nullable', 'string'],
        ]);

        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $items = array_map(fn($i) => $i + [
            'product_id'  => $i['product_id'] ?? '',
            'name'        => $i['name'] ?? '',
            'description' => $i['description'] ?? '',
            'qty'         => (int)($i['qty'] ?? 0),
            'uom_id'      => $i['uom_id'] ?? '',
            'point'       => (int)($i['point'] ?? 0),
            'total_point' => (int)($i['total_point'] ?? 0),
        ], $items);

        $this->store->create($request->only('customer_id','member_id','name','point_reguler','point_promo','point_type','doc_id','date','warehouse_id','user','type_name_id','note','total_point_claim') + [
            'items' => $items,
        ]);
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
            'customer_id'      => ['required', 'string', 'max:50'],
            'member_id'        => ['nullable', 'string', 'max:50'],
            'name'             => ['required', 'string', 'max:200'],
            'point_reguler'    => ['nullable', 'integer', 'min:0'],
            'point_promo'      => ['nullable', 'integer', 'min:0'],
            'point_type'       => ['nullable', 'string', 'max:50'],
            'doc_id'           => ['required', 'string', 'max:50'],
            'date'             => ['required', 'date'],
            'warehouse_id'     => ['nullable', 'string', 'max:50'],
            'user'             => ['nullable', 'string', 'max:100'],
            'type_name_id'     => ['nullable', 'string', 'max:50'],
            'note'             => ['nullable', 'string'],
            'total_point_claim'=> ['required', 'integer', 'min:0'],
            'items'            => ['nullable', 'string'],
        ]);

        $items = $request->input('items') ? json_decode($request->input('items'), true) : [];
        $items = array_map(fn($i) => $i + [
            'product_id'  => $i['product_id'] ?? '',
            'name'        => $i['name'] ?? '',
            'description' => $i['description'] ?? '',
            'qty'         => (int)($i['qty'] ?? 0),
            'uom_id'      => $i['uom_id'] ?? '',
            'point'       => (int)($i['point'] ?? 0),
            'total_point' => (int)($i['total_point'] ?? 0),
        ], $items);

        $this->store->update($id, $request->only('customer_id','member_id','name','point_reguler','point_promo','point_type','doc_id','date','warehouse_id','user','type_name_id','note','total_point_claim') + [
            'items' => $items,
        ]);
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}
