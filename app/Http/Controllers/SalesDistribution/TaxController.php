<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class TaxController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('tax');
        View::share('activeMenu', 'tax');
    }

    public function index()
    {
        return view('Sales-distribution.tax.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['tax_doc_no'] ?? '', $q) !== false ||
                stripos($i['invoice_no'] ?? '', $q) !== false ||
                stripos($i['customer_npwp'] ?? '', $q) !== false ||
                stripos($i['tax_invoice_no'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_tax_code')) {
            $s = $request->filter_tax_code;
            if ($s !== 'all') $data = array_filter($data, fn($i) => ($i['tax_code'] ?? '') === $s);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('dpp_fmt', fn($r) => 'Rp ' . number_format((int)($r['dpp_amount'] ?? 0), 0, ',', '.'))
            ->addColumn('tax_amount_fmt', fn($r) => 'Rp ' . number_format((int)($r['tax_amount'] ?? 0), 0, ',', '.'))
            ->addColumn('tax_code_badge', function ($row) {
                $map = ['PPN'=>'bg-primary','PPh'=>'bg-warning text-dark'];
                $c = $row['tax_code'] ?? 'PPN';
                return '<span class="badge ' . ($map[$c]??'bg-secondary') . '">' . $c . '</span>';
            })
            ->addColumn('status_badge', function ($row) {
                $m = ['DRAFT'=>'bg-secondary','EXPORTED'=>'bg-success','PENDING'=>'bg-warning text-dark','FAILED'=>'bg-danger'];
                $s = $row['status'] ?? 'DRAFT';
                return '<span class="badge ' . ($m[$s]??'bg-secondary') . '">' . $s . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $row['id'] . '"><i class="bi bi-pencil"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['tax_code_badge','status_badge','action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tax_doc_no'    => ['required','string','max:50'],
            'invoice_no'    => ['required','string','max:50'],
            'tax_code'      => ['required','string','max:10'],
            'customer_npwp' => ['nullable','string','max:30'],
            'dpp_amount'    => ['required','numeric','min:0'],
            'tax_amount'    => ['required','numeric','min:0'],
            'tax_invoice_no'=> ['nullable','string','max:50'],
            'status'        => ['nullable','string','max:50'],
        ]);
        $this->store->create($request->only('tax_doc_no','invoice_no','tax_code','customer_npwp','dpp_amount','tax_amount','tax_invoice_no','status'));
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
            'tax_doc_no'    => ['required','string','max:50'],
            'invoice_no'    => ['required','string','max:50'],
            'tax_code'      => ['required','string','max:10'],
            'customer_npwp' => ['nullable','string','max:30'],
            'dpp_amount'    => ['required','numeric','min:0'],
            'tax_amount'    => ['required','numeric','min:0'],
            'tax_invoice_no'=> ['nullable','string','max:50'],
            'status'        => ['nullable','string','max:50'],
        ]);
        $this->store->update($id, $request->only('tax_doc_no','invoice_no','tax_code','customer_npwp','dpp_amount','tax_amount','tax_invoice_no','status'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}