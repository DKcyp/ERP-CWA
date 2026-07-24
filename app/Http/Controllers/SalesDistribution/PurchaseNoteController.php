<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class PurchaseNoteController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('purchase-note');
        View::share('activeMenu', 'purchase-note');
    }

    public function index()
    {
        return view('Sales-distribution.purchase-note.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['note_no'] ?? '', $q) !== false ||
                stripos($i['customer_id'] ?? '', $q) !== false ||
                stripos($i['po_customer_no'] ?? '', $q) !== false ||
                stripos($i['description'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_validation')) {
            $s = $request->filter_validation;
            if ($s !== 'all') $data = array_filter($data, fn($i) => ($i['validation_status'] ?? '') === $s);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('validation_badge', function ($row) {
                $map = ['UNVERIFIED'=>'bg-secondary','VERIFIED'=>'bg-success','REJECTED'=>'bg-danger'];
                $s = $row['validation_status'] ?? 'UNVERIFIED';
                return '<span class="badge ' . ($map[$s]??'bg-secondary') . '">' . $s . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $row['id'] . '"><i class="bi bi-pencil"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['validation_badge','action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'note_no'          => ['required','string','max:50'],
            'date'             => ['required','date'],
            'customer_id'      => ['required','string','max:50'],
            'po_customer_no'   => ['nullable','string','max:50'],
            'attachment'       => ['nullable','string','max:255'],
            'description'      => ['nullable','string','max:500'],
            'validation_status'=> ['nullable','string','max:50'],
        ]);
        $this->store->create($request->only('note_no','date','customer_id','po_customer_no','attachment','description','validation_status'));
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
            'note_no'          => ['required','string','max:50'],
            'date'             => ['required','date'],
            'customer_id'      => ['required','string','max:50'],
            'po_customer_no'   => ['nullable','string','max:50'],
            'attachment'       => ['nullable','string','max:255'],
            'description'      => ['nullable','string','max:500'],
            'validation_status'=> ['nullable','string','max:50'],
        ]);
        $this->store->update($id, $request->only('note_no','date','customer_id','po_customer_no','attachment','description','validation_status'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}