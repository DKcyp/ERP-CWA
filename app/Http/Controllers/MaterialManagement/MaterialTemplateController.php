<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class MaterialTemplateController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('material_template');
        View::share('activeMenu', 'material-template');
    }

    public function index()
    {
        return view('material-management.material-template.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['template_code'] ?? '', $q) !== false ||
                stripos($i['template_name'] ?? '', $q) !== false ||
                stripos($i['target_material'] ?? '', $q) !== false ||
                stripos($i['raw_material'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('qty_output_fmt', fn($r) => number_format((int)($r['target_output_qty'] ?? 0), 0, ',', '.'))
            ->addColumn('qty_needed_fmt', fn($r) => number_format((int)($r['qty_needed'] ?? 0), 0, ',', '.'))
            ->addColumn('action', fn($row) => '<div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-primary btn-edit" data-id="'.$row['id'].'"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-outline-danger btn-delete" data-id="'.$row['id'].'"><i class="bi bi-trash"></i></button>
            </div>')
            ->rawColumns(['action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'template_code'    => ['required','string','max:50'],
            'template_name'    => ['required','string','max:200'],
            'target_material'  => ['nullable','string','max:200'],
            'target_output_qty'=> ['required','numeric','min:0'],
            'raw_material'     => ['nullable','string','max:200'],
            'qty_needed'       => ['required','numeric','min:0'],
            'uom_id'           => ['nullable','string','max:50'],
            'description'      => ['nullable','string','max:500'],
        ]);
        $this->store->create($request->only('template_code','template_name','target_material','target_output_qty','raw_material','qty_needed','uom_id','description'));
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
            'template_code'    => ['required','string','max:50'],
            'template_name'    => ['required','string','max:200'],
            'target_material'  => ['nullable','string','max:200'],
            'target_output_qty'=> ['required','numeric','min:0'],
            'raw_material'     => ['nullable','string','max:200'],
            'qty_needed'       => ['required','numeric','min:0'],
            'uom_id'           => ['nullable','string','max:50'],
            'description'      => ['nullable','string','max:500'],
        ]);
        $this->store->update($id, $request->only('template_code','template_name','target_material','target_output_qty','raw_material','qty_needed','uom_id','description'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}