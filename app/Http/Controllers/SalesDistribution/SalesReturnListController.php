<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SalesReturnListController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('sales-return-list');
        View::share('activeMenu', 'sales-return-list');
    }

    public function index()
    {
        return view('Sales-distribution.sales-return-list.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['no'] ?? '', $q) !== false ||
                stripos($i['customer_id'] ?? '', $q) !== false ||
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['si_returned'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_status')) {
            $s = $request->filter_status;
            if ($s !== 'all') $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $s);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('disc_pct_fmt', fn($r) => ($r['disc_pct'] ?? 0) . '%')
            ->addColumn('disc_amt_fmt', fn($r) => 'Rp ' . number_format((int)($r['disc_amt'] ?? 0), 0, ',', '.'))
            ->addColumn('total_fmt', fn($r) => 'Rp ' . number_format((int)($r['total'] ?? 0), 0, ',', '.'))
            ->addColumn('status_badge', function ($row) {
                $map = ['DRAFT'=>'bg-secondary','SUBMITTED'=>'bg-primary','APPROVED'=>'bg-success','REJECTED'=>'bg-danger','PROCESSED'=>'bg-info text-dark'];
                $s = $row['status'] ?? 'DRAFT';
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
            'no'          => ['required','string','max:50'],
            'date'        => ['required','date'],
            'warehouse'   => ['nullable','string','max:100'],
            'customer_id' => ['required','string','max:50'],
            'name'        => ['required','string','max:100'],
            'area'        => ['nullable','string','max:100'],
            'wa'          => ['nullable','string','max:30'],
            'disc_pct'    => ['nullable','numeric','min:0','max:100'],
            'disc_amt'    => ['nullable','numeric','min:0'],
            'total'       => ['required','numeric','min:0'],
            'currency'    => ['nullable','string','max:10'],
            'status'      => ['nullable','string','max:50'],
            'note'        => ['nullable','string','max:500'],
            'term'        => ['nullable','string','max:50'],
            'sales'       => ['nullable','string','max:100'],
            'si_returned' => ['nullable','string','max:50'],
        ]);
        $this->store->create($request->only('no','date','warehouse','customer_id','name','area','wa','disc_pct','disc_amt','total','currency','status','note','term','sales','si_returned'));
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
            'no'          => ['required','string','max:50'],
            'date'        => ['required','date'],
            'warehouse'   => ['nullable','string','max:100'],
            'customer_id' => ['required','string','max:50'],
            'name'        => ['required','string','max:100'],
            'area'        => ['nullable','string','max:100'],
            'wa'          => ['nullable','string','max:30'],
            'disc_pct'    => ['nullable','numeric','min:0','max:100'],
            'disc_amt'    => ['nullable','numeric','min:0'],
            'total'       => ['required','numeric','min:0'],
            'currency'    => ['nullable','string','max:10'],
            'status'      => ['nullable','string','max:50'],
            'note'        => ['nullable','string','max:500'],
            'term'        => ['nullable','string','max:50'],
            'sales'       => ['nullable','string','max:100'],
            'si_returned' => ['nullable','string','max:50'],
        ]);
        $this->store->update($id, $request->only('no','date','warehouse','customer_id','name','area','wa','disc_pct','disc_amt','total','currency','status','note','term','sales','si_returned'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}