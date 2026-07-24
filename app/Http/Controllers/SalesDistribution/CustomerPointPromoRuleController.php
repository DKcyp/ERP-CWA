<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class CustomerPointPromoRuleController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('point-promo-rules');
        View::share('activeMenu', 'customer-point-promo-rule');
    }

    public function index()
    {
        return view('Sales-distribution.customer-point-promo-rule.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();
        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) => stripos($i['name'] ?? '', $q) !== false || stripos($i['category_id'] ?? '', $q) !== false);
        }
        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('action', fn($r) => '<div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $r['id'] . '"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $r['id'] . '"><i class="bi bi-trash"></i></button>
            </div>')
            ->rawColumns(['action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'   => ['required', 'string', 'max:50'],
            'name'          => ['required', 'string', 'max:200'],
            'point_per_qty' => ['required', 'integer', 'min:1'],
            'uom_id'        => ['nullable', 'string', 'max:50'],
        ]);
        $this->store->create($request->only('category_id','name','point_per_qty','uom_id'));
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
            'category_id'   => ['required', 'string', 'max:50'],
            'name'          => ['required', 'string', 'max:200'],
            'point_per_qty' => ['required', 'integer', 'min:1'],
            'uom_id'        => ['nullable', 'string', 'max:50'],
        ]);
        $this->store->update($id, $request->only('category_id','name','point_per_qty','uom_id'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}
