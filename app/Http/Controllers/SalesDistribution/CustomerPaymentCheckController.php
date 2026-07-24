<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class CustomerPaymentCheckController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('customer-payment-check');
        View::share('activeMenu', 'customer-payment-check');
    }

    public function index()
    {
        return view('Sales-distribution.customer-payment-check.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['check_no'] ?? '', $q) !== false ||
                stripos($i['bank_name'] ?? '', $q) !== false ||
                stripos($i['customer_id'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_status')) {
            $s = $request->filter_status;
            if ($s !== 'all') $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $s);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('maturity_date_fmt', fn($r) => $r['maturity_date'] ? \Carbon\Carbon::parse($r['maturity_date'])->format('d/m/Y') : '-')
            ->addColumn('amount_fmt', fn($r) => 'Rp ' . number_format((int)($r['amount'] ?? 0), 0, ',', '.'))
            ->addColumn('status_badge', function ($row) {
                $map = ['CLEARING'=>'bg-warning text-dark','BOUNCED'=>'bg-danger','PASSED'=>'bg-success'];
                $s = $row['status'] ?? 'CLEARING';
                return '<span class="badge ' . ($map[$s]??'bg-secondary') . '">' . $s . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $row['id'] . '"><i class="bi bi-pencil"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['status_badge','action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'check_no'      => ['required','string','max:50'],
            'bank_name'     => ['required','string','max:100'],
            'maturity_date' => ['required','date'],
            'customer_id'   => ['required','string','max:50'],
            'amount'        => ['required','numeric','min:0'],
            'status'        => ['nullable','string','max:50'],
        ]);
        $this->store->create($request->only('check_no','bank_name','maturity_date','customer_id','amount','status'));
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
            'check_no'      => ['required','string','max:50'],
            'bank_name'     => ['required','string','max:100'],
            'maturity_date' => ['required','date'],
            'customer_id'   => ['required','string','max:50'],
            'amount'        => ['required','numeric','min:0'],
            'status'        => ['nullable','string','max:50'],
        ]);
        $this->store->update($id, $request->only('check_no','bank_name','maturity_date','customer_id','amount','status'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}