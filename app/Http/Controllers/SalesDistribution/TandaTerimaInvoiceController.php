<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class TandaTerimaInvoiceController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('tanda-terima-invoice');
        View::share('activeMenu', 'tanda-terima-invoice');
    }

    public function index()
    {
        return view('Sales-distribution.tanda-terima-invoice.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['tti_no'] ?? '', $q) !== false ||
                stripos($i['customer_id'] ?? '', $q) !== false ||
                stripos($i['customer_name'] ?? '', $q) !== false ||
                stripos($i['received_by'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_return_status')) {
            $s = $request->filter_return_status;
            if ($s !== 'all') $data = array_filter($data, fn($i) => ($i['return_status'] ?? '') === $s);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('received_date_fmt', fn($r) => $r['received_date'] ? \Carbon\Carbon::parse($r['received_date'])->format('d/m/Y') : '-')
            ->addColumn('return_status_badge', function ($row) {
                $map = ['NONE'=>'bg-secondary','PARTIAL'=>'bg-warning text-dark','FULL'=>'bg-success'];
                $s = $row['return_status'] ?? 'NONE';
                return '<span class="badge ' . ($map[$s]??'bg-secondary') . '">' . $s . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $row['id'] . '"><i class="bi bi-pencil"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['return_status_badge','action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tti_no'         => ['required','string','max:50'],
            'date'           => ['required','date'],
            'customer_id'    => ['required','string','max:50'],
            'customer_name'  => ['required','string','max:100'],
            'invoice_list'   => ['nullable','string'],
            'received_by'    => ['nullable','string','max:100'],
            'received_date'  => ['nullable','date'],
            'return_status'  => ['nullable','string','max:50'],
        ]);
        $this->store->create($request->only('tti_no','date','customer_id','customer_name','invoice_list','received_by','received_date','return_status'));
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
            'tti_no'         => ['required','string','max:50'],
            'date'           => ['required','date'],
            'customer_id'    => ['required','string','max:50'],
            'customer_name'  => ['required','string','max:100'],
            'invoice_list'   => ['nullable','string'],
            'received_by'    => ['nullable','string','max:100'],
            'received_date'  => ['nullable','date'],
            'return_status'  => ['nullable','string','max:50'],
        ]);
        $this->store->update($id, $request->only('tti_no','date','customer_id','customer_name','invoice_list','received_by','received_date','return_status'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}