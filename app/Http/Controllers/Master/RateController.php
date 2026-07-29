<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use View;

class RateController extends Controller
{
    protected DummyStore $store;
    protected DummyStore $currencyStore;

    public function __construct()
    {
        $this->store = new DummyStore('rate');
        $this->currencyStore = new DummyStore('currency');
        View::share('activeMenu', 'rate');
    }

    public function index()
    {
        $currencies = $this->currencyStore->all();
        return view('master.rate.index', compact('currencies'));
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_currency')) {
            $q = $request->filter_currency;
            $data = array_filter($data, fn($i) => stripos($i['currency'] ?? '', $q) !== false);
        }
        if ($request->filled('filter_date_from')) {
            $data = array_filter($data, fn($i) => ($i['rate_date'] ?? '') >= $request->filter_date_from);
        }
        if ($request->filled('filter_date_to')) {
            $data = array_filter($data, fn($i) => ($i['rate_date'] ?? '') <= $request->filter_date_to);
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
            'currency'   => ['required','string','max:10'],
            'rate_date'  => ['required','date'],
            'rate_value' => ['required','numeric','min:0'],
        ]);
        $payload = $request->only('currency','rate_date','rate_value');
        $payload['updated_by'] = Auth::check() ? Auth::user()->name : 'System';
        $this->store->create($payload);
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
            'currency'   => ['required','string','max:10'],
            'rate_date'  => ['required','date'],
            'rate_value' => ['required','numeric','min:0'],
        ]);
        $payload = $request->only('currency','rate_date','rate_value');
        $payload['updated_by'] = Auth::check() ? Auth::user()->name : 'System';
        $this->store->update($id, $payload);
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}