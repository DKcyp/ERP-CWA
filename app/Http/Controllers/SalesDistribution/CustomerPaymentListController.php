<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class CustomerPaymentListController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('customer-payment-list');
        View::share('activeMenu', 'customer-payment-list');
    }

    public function index()
    {
        return view('Sales-distribution.customer-payment-list.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['payment_no'] ?? '', $q) !== false ||
                stripos($i['customer_id'] ?? '', $q) !== false ||
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['no_ttp'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_status')) {
            $s = $request->filter_status;
            if ($s !== 'all') $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $s);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('date_complete_fmt', fn($r) => $r['date_complete'] ? \Carbon\Carbon::parse($r['date_complete'])->format('d/m/Y') : '-')
            ->addColumn('total_fmt', fn($r) => 'Rp ' . number_format((int)($r['total'] ?? 0), 0, ',', '.'))
            ->addColumn('status_badge', function ($row) {
                $map = ['DRAFT'=>'bg-secondary','RECEIVED'=>'bg-primary','CONFIRMED'=>'bg-success','VOID'=>'bg-danger'];
                $s = $row['status'] ?? 'DRAFT';
                return '<span class="badge ' . ($map[$s]??'bg-secondary') . '">' . $s . '</span>';
            })
            ->addColumn('type_badge', function ($row) {
                $t = $row['type_payment'] ?? 'Reguler';
                $c = $t === 'Down' ? 'bg-info text-dark' : 'bg-light text-dark';
                return '<span class="badge ' . $c . '">' . $t . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $row['id'] . '"><i class="bi bi-pencil"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['status_badge','type_badge','action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_no'    => ['required','string','max:50'],
            'date'          => ['required','date'],
            'date_complete' => ['nullable','date'],
            'warehouse'     => ['nullable','string','max:100'],
            'no_ttp'        => ['nullable','string','max:50'],
            'customer_id'   => ['required','string','max:50'],
            'name'          => ['required','string','max:100'],
            'account'       => ['nullable','string','max:100'],
            'total'         => ['required','numeric','min:0'],
            'status'        => ['nullable','string','max:50'],
            'currency'      => ['nullable','string','max:10'],
            'rate'          => ['nullable','numeric','min:0'],
            'note'          => ['nullable','string','max:500'],
            'def_sales'     => ['nullable','string','max:100'],
            'type_payment'  => ['nullable','string','max:50'],
        ]);
        $this->store->create($request->only('payment_no','date','date_complete','warehouse','no_ttp','customer_id','name','account','total','status','currency','rate','note','def_sales','type_payment'));
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
            'payment_no'    => ['required','string','max:50'],
            'date'          => ['required','date'],
            'date_complete' => ['nullable','date'],
            'warehouse'     => ['nullable','string','max:100'],
            'no_ttp'        => ['nullable','string','max:50'],
            'customer_id'   => ['required','string','max:50'],
            'name'          => ['required','string','max:100'],
            'account'       => ['nullable','string','max:100'],
            'total'         => ['required','numeric','min:0'],
            'status'        => ['nullable','string','max:50'],
            'currency'      => ['nullable','string','max:10'],
            'rate'          => ['nullable','numeric','min:0'],
            'note'          => ['nullable','string','max:500'],
            'def_sales'     => ['nullable','string','max:100'],
            'type_payment'  => ['nullable','string','max:50'],
        ]);
        $this->store->update($id, $request->only('payment_no','date','date_complete','warehouse','no_ttp','customer_id','name','account','total','status','currency','rate','note','def_sales','type_payment'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}