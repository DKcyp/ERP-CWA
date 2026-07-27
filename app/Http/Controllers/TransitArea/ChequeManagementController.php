<?php

namespace App\Http\Controllers\TransitArea;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ChequeManagementController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('cheque-management');
        View::share('activeMenu', 'cheque-management');
    }

    public function index()
    {
        return view('transit-area.cheque-management.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['no_bg'] ?? '', $q) !== false ||
                stripos($i['customer_id'] ?? '', $q) !== false ||
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['bank'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_valid')) {
            $s = $request->filter_valid;
            if ($s !== 'all') $data = array_filter($data, fn($i) => ($i['valid'] ?? '') === $s);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('valid_date_fmt', fn($r) => $r['valid_date'] ? \Carbon\Carbon::parse($r['valid_date'])->format('d/m/Y') : '-')
            ->addColumn('amount_fmt', fn($r) => 'Rp ' . number_format((int)($r['amount'] ?? 0), 0, ',', '.'))
            ->addColumn('valid_badge', function ($row) {
                $map = ['YES'=>'bg-success','NO'=>'bg-danger','PENDING'=>'bg-warning text-dark'];
                $s = $row['valid'] ?? 'PENDING';
                return '<span class="badge ' . ($map[$s]??'bg-secondary') . '">' . $s . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $row['id'] . '"><i class="bi bi-pencil"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['valid_badge','action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'        => ['required','date'],
            'customer_id' => ['required','string','max:50'],
            'name'        => ['required','string','max:100'],
            'no_bg'       => ['required','string','max:50'],
            'bank'        => ['required','string','max:100'],
            'valid_date'  => ['required','date'],
            'amount'      => ['required','numeric','min:0'],
            'valid'       => ['nullable','string','max:20'],
            'note'        => ['nullable','string','max:500'],
            'payment'     => ['nullable','string','max:100'],
        ]);
        $this->store->create($request->only('date','customer_id','name','no_bg','bank','valid_date','amount','valid','note','payment'));
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
            'date'        => ['required','date'],
            'customer_id' => ['required','string','max:50'],
            'name'        => ['required','string','max:100'],
            'no_bg'       => ['required','string','max:50'],
            'bank'        => ['required','string','max:100'],
            'valid_date'  => ['required','date'],
            'amount'      => ['required','numeric','min:0'],
            'valid'       => ['nullable','string','max:20'],
            'note'        => ['nullable','string','max:500'],
            'payment'     => ['nullable','string','max:100'],
        ]);
        $this->store->update($id, $request->only('date','customer_id','name','no_bg','bank','valid_date','amount','valid','note','payment'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}