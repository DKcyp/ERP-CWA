<?php

namespace App\Http\Controllers\TransitArea;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class PmbController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('pmb');
        View::share('activeMenu', 'pmb');
    }

    public function index()
    {
        return view('transit-area.pmb.index');
    }

    public function table()
    {
        $data = $this->store->all();
        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('target_collection_fmt', fn($r) => 'Rp ' . number_format((int)($r['target_collection'] ?? 0), 0, ',', '.'))
            ->addColumn('achieved_collection_fmt', fn($r) => 'Rp ' . number_format((int)($r['achieved_collection'] ?? 0), 0, ',', '.'))
            ->addColumn('incentive_rate_fmt', fn($r) => number_format((float)($r['incentive_rate'] ?? 0), 2) . ' %')
            ->addColumn('penalty_amount_fmt', fn($r) => 'Rp ' . number_format((int)($r['penalty_amount'] ?? 0), 0, ',', '.'))
            ->addColumn('total_pmb_bonus_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_pmb_bonus'] ?? 0), 0, ',', '.'))
            ->addColumn('status_badge', function($r) {
                $map = ['Aktif'=>'success','Tidak Aktif'=>'secondary','Pending'=>'warning'];
                $c = $map[$r['status']??'']??'secondary';
                return '<span class="badge bg-'.$c.'">'.($r['status']??'-').'</span>';
            })
            ->addColumn('action', fn($row) => '<div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-primary btn-edit" data-id="'.$row['id'].'"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-outline-danger btn-delete" data-id="'.$row['id'].'"><i class="bi bi-trash"></i></button>
            </div>')
            ->rawColumns(['status_badge','action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'period'             => ['required','string','max:20'],
            'transit_area'       => ['nullable','string','max:100'],
            'salesman_id'        => ['nullable','string','max:50'],
            'target_collection'  => ['required','numeric','min:0'],
            'achieved_collection'=> ['required','numeric','min:0'],
            'incentive_rate'     => ['required','numeric','min:0'],
            'penalty_amount'     => ['required','numeric','min:0'],
            'total_pmb_bonus'    => ['required','numeric','min:0'],
            'status'             => ['required','string','max:20'],
        ]);
        $this->store->create($request->only('period','transit_area','salesman_id','target_collection','achieved_collection','incentive_rate','penalty_amount','total_pmb_bonus','status'));
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
            'period'             => ['required','string','max:20'],
            'transit_area'       => ['nullable','string','max:100'],
            'salesman_id'        => ['nullable','string','max:50'],
            'target_collection'  => ['required','numeric','min:0'],
            'achieved_collection'=> ['required','numeric','min:0'],
            'incentive_rate'     => ['required','numeric','min:0'],
            'penalty_amount'     => ['required','numeric','min:0'],
            'total_pmb_bonus'    => ['required','numeric','min:0'],
            'status'             => ['required','string','max:20'],
        ]);
        $this->store->update($id, $request->only('period','transit_area','salesman_id','target_collection','achieved_collection','incentive_rate','penalty_amount','total_pmb_bonus','status'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}