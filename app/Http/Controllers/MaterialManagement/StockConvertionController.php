<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class StockConvertionController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('stock_convertion');
        View::share('activeMenu', 'stock-convertion');
    }

    public function index()
    {
        return view('material-management.stock-convertion.index');
    }

    public function table()
    {
        $data = $this->store->all();
        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('qty_produced_fmt', fn($r) => number_format((int)($r['qty_produced'] ?? 0), 0, ',', '.'))
            ->addColumn('qty_consumed_fmt', fn($r) => number_format((int)($r['qty_consumed'] ?? 0), 0, ',', '.'))
            ->addColumn('action', fn($row) => '<div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-primary btn-edit" data-id="'.$row['id'].'"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-outline-danger btn-delete" data-id="'.$row['id'].'"><i class="bi bi-trash"></i></button>
            </div>')
            ->rawColumns(['action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'conversion_no'     => ['required','string','max:50'],
            'date'              => ['required','date'],
            'warehouse_id'      => ['nullable','string','max:100'],
            'material_template' => ['nullable','string','max:200'],
            'output_material'   => ['nullable','string','max:200'],
            'qty_produced'      => ['required','numeric','min:0'],
            'raw_material'      => ['nullable','string','max:200'],
            'qty_consumed'      => ['required','numeric','min:0'],
            'notes'             => ['nullable','string','max:500'],
        ]);
        $this->store->create($request->only('conversion_no','date','warehouse_id','material_template','output_material','qty_produced','raw_material','qty_consumed','notes'));
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
            'conversion_no'     => ['required','string','max:50'],
            'date'              => ['required','date'],
            'warehouse_id'      => ['nullable','string','max:100'],
            'material_template' => ['nullable','string','max:200'],
            'output_material'   => ['nullable','string','max:200'],
            'qty_produced'      => ['required','numeric','min:0'],
            'raw_material'      => ['nullable','string','max:200'],
            'qty_consumed'      => ['required','numeric','min:0'],
            'notes'             => ['nullable','string','max:500'],
        ]);
        $this->store->update($id, $request->only('conversion_no','date','warehouse_id','material_template','output_material','qty_produced','raw_material','qty_consumed','notes'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}