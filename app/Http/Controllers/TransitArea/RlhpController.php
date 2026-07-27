<?php

namespace App\Http\Controllers\TransitArea;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class RlhpController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('rlhp');
        View::share('activeMenu', 'rlhp');
    }

    public function index()
    {
        return view('transit-area.rlhp.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['doc_id'] ?? '', $q) !== false ||
                stripos($i['depo'] ?? '', $q) !== false ||
                stripos($i['tipe'] ?? '', $q) !== false ||
                stripos($i['user_id'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('doc_date_fmt', fn($r) => $r['doc_date'] ? \Carbon\Carbon::parse($r['doc_date'])->format('d/m/Y') : '-')
            ->addColumn('payment_from_fmt', fn($r) => $r['payment_from_date'] ? \Carbon\Carbon::parse($r['payment_from_date'])->format('d/m/Y') : '-')
            ->addColumn('payment_to_fmt', fn($r) => $r['payment_to_date'] ? \Carbon\Carbon::parse($r['payment_to_date'])->format('d/m/Y') : '-')
            ->addColumn('total_cash_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_cash'] ?? 0), 0, ',', '.'))
            ->addColumn('total_giro_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_giro'] ?? 0), 0, ',', '.'))
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
            'doc_id'           => ['required','string','max:50'],
            'doc_date'         => ['required','date'],
            'payment_from_date'=> ['required','date'],
            'payment_to_date'  => ['required','date'],
            'depo'             => ['nullable','string','max:100'],
            'tipe'             => ['nullable','string','max:50'],
            'total_cash'       => ['required','numeric','min:0'],
            'total_giro'       => ['required','numeric','min:0'],
            'notes'            => ['nullable','string','max:500'],
            'user_id'          => ['nullable','string','max:50'],
        ]);
        $this->store->create($request->only('doc_id','doc_date','payment_from_date','payment_to_date','depo','tipe','total_cash','total_giro','notes','user_id'));
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
            'doc_id'           => ['required','string','max:50'],
            'doc_date'         => ['required','date'],
            'payment_from_date'=> ['required','date'],
            'payment_to_date'  => ['required','date'],
            'depo'             => ['nullable','string','max:100'],
            'tipe'             => ['nullable','string','max:50'],
            'total_cash'       => ['required','numeric','min:0'],
            'total_giro'       => ['required','numeric','min:0'],
            'notes'            => ['nullable','string','max:500'],
            'user_id'          => ['nullable','string','max:50'],
        ]);
        $this->store->update($id, $request->only('doc_id','doc_date','payment_from_date','payment_to_date','depo','tipe','total_cash','total_giro','notes','user_id'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}