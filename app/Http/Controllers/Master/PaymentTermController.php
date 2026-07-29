<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class PaymentTermController extends Controller
{
    protected DummyStore $store;
    protected DummyStore $salesDiscountStore;

    public function __construct()
    {
        $this->store = new DummyStore('payment-term');
        $this->salesDiscountStore = new DummyStore('sales-discount');
        View::share('activeMenu', 'payment-term');
    }

    public function index()
    {
        $salesDiscounts = $this->salesDiscountStore->all();
        return view('master.payment-term.index', compact('salesDiscounts'));
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['term_id'] ?? '', $q) !== false ||
                stripos($i['sales_discount'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('yn_badge', fn($r, $k) => ($r[$k] ?? 'N') === 'Y'
                ? '<span class="badge bg-success">Yes</span>'
                : '<span class="badge bg-secondary">No</span>')
            ->addColumn('action', fn($row) => '<div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-primary btn-edit" data-id="'.$row['id'].'"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-outline-danger btn-delete" data-id="'.$row['id'].'"><i class="bi bi-trash"></i></button>
            </div>')
            ->rawColumns(['yn_badge', 'action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_discount_percent' => ['nullable','numeric','min:0','max:100'],
            'if_paid_within_days'      => ['nullable','integer','min:0'],
            'net_due_in_days'          => ['required','integer','min:0'],
            'cash_on_delivery'         => ['required','string','in:Y,N'],
            'default_term_for_not_cod' => ['required','string','in:Y,N'],
            'sales_discount'           => ['nullable','string','max:200'],
        ]);
        $this->store->create($request->only('payment_discount_percent','if_paid_within_days','net_due_in_days','cash_on_delivery','default_term_for_not_cod','sales_discount'));
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
            'payment_discount_percent' => ['nullable','numeric','min:0','max:100'],
            'if_paid_within_days'      => ['nullable','integer','min:0'],
            'net_due_in_days'          => ['required','integer','min:0'],
            'cash_on_delivery'         => ['required','string','in:Y,N'],
            'default_term_for_not_cod' => ['required','string','in:Y,N'],
            'sales_discount'           => ['nullable','string','max:200'],
        ]);
        $this->store->update($id, $request->only('payment_discount_percent','if_paid_within_days','net_due_in_days','cash_on_delivery','default_term_for_not_cod','sales_discount'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}