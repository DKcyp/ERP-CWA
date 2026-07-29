<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class PromoBuyNGetMController extends Controller
{
    protected DummyStore $store;
    protected DummyStore $productStore;

    public function __construct()
    {
        $this->store = new DummyStore('promo-buy-n-get-m');
        $this->productStore = new DummyStore('product');
        View::share('activeMenu', 'promo-buy-n-get-m');
    }

    public function index()
    {
        $products = $this->productStore->all();
        return view('master.promo-buy-n-get-m.index', compact('products'));
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['buy_product_name'] ?? '', $q) !== false ||
                stripos($i['get_product_name'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('action', fn($row) => '<div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-primary btn-edit" data-id="'.$row['id'].'"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-outline-danger btn-delete" data-id="'.$row['id'].'"><i class="bi bi-trash"></i></button>
            </div>')
            ->rawColumns(['action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                           => ['required','string','max:200'],
            'date_from'                      => ['required','date'],
            'date_to'                        => ['nullable','date','after_or_equal:date_from'],
            'buy_product_id'                 => ['required','string','max:50'],
            'buy_product_name'               => ['required','string','max:200'],
            'buy_qty'                        => ['required','integer','min:1'],
            'get_product_id'                 => ['required','string','max:50'],
            'get_product_name'               => ['required','string','max:200'],
            'get_qty'                        => ['required','integer','min:1'],
            'get_discount_amount'            => ['nullable','numeric','min:0'],
            'get_discount_percentage'        => ['nullable','numeric','min:0','max:100'],
            'sales_invoice_discount_amount'  => ['nullable','numeric','min:0'],
            'sales_invoice_discount_percentage' => ['nullable','numeric','min:0','max:100'],
        ]);
        $this->store->create($request->only(
            'name','date_from','date_to',
            'buy_product_id','buy_product_name','buy_qty',
            'get_product_id','get_product_name','get_qty',
            'get_discount_amount','get_discount_percentage',
            'sales_invoice_discount_amount','sales_invoice_discount_percentage'
        ));
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
            'name'                           => ['required','string','max:200'],
            'date_from'                      => ['required','date'],
            'date_to'                        => ['nullable','date','after_or_equal:date_from'],
            'buy_product_id'                 => ['required','string','max:50'],
            'buy_product_name'               => ['required','string','max:200'],
            'buy_qty'                        => ['required','integer','min:1'],
            'get_product_id'                 => ['required','string','max:50'],
            'get_product_name'               => ['required','string','max:200'],
            'get_qty'                        => ['required','integer','min:1'],
            'get_discount_amount'            => ['nullable','numeric','min:0'],
            'get_discount_percentage'        => ['nullable','numeric','min:0','max:100'],
            'sales_invoice_discount_amount'  => ['nullable','numeric','min:0'],
            'sales_invoice_discount_percentage' => ['nullable','numeric','min:0','max:100'],
        ]);
        $this->store->update($id, $request->only(
            'name','date_from','date_to',
            'buy_product_id','buy_product_name','buy_qty',
            'get_product_id','get_product_name','get_qty',
            'get_discount_amount','get_discount_percentage',
            'sales_invoice_discount_amount','sales_invoice_discount_percentage'
        ));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}