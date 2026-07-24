<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SalesOrderListController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('sales-orders');
        View::share('activeMenu', 'sales-order-list');
    }

    public function index()
    {
        return view('Sales-distribution.sales-order-list.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['customer_id'] ?? '', $q) !== false ||
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['warehouse'] ?? '', $q) !== false ||
                stripos($i['sales'] ?? '', $q) !== false ||
                stripos($i['contract_no'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_status')) {
            $s = $request->filter_status;
            if ($s !== 'all') $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $s);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('disc_amt_fmt', fn($r) => number_format((int)($r['disc_amt'] ?? 0), 0, ',', '.'))
            ->addColumn('total_fmt', fn($r) => number_format((int)($r['total'] ?? 0), 0, ',', '.'))
            ->addColumn('status_badge', function ($row) {
                $map = [
                    'DRAFT'     => ['class' => 'bg-secondary', 'label' => 'Draft'],
                    'APPROVED'  => ['class' => 'bg-info text-dark', 'label' => 'Approved'],
                    'PROCESS'   => ['class' => 'bg-warning text-dark', 'label' => 'Process'],
                    'COMPLETED' => ['class' => 'bg-success', 'label' => 'Completed'],
                    'CANCELED'  => ['class' => 'bg-danger', 'label' => 'Canceled'],
                ];
                $s = $row['status'] ?? 'DRAFT';
                $c = $map[$s]['class'] ?? 'bg-secondary';
                $l = $map[$s]['label'] ?? $s;
                return '<span class="badge ' . $c . '">' . $l . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $row['id'] . '"><i class="bi bi-pencil"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['status_badge', 'action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'        => ['required', 'date'],
            'warehouse'   => ['required', 'string', 'max:100'],
            'customer_id' => ['required', 'string', 'max:50'],
            'name'        => ['required', 'string', 'max:200'],
            'area'        => ['nullable', 'string', 'max:100'],
            'wa'          => ['nullable', 'string', 'max:50'],
            'note'        => ['nullable', 'string'],
            'disc_pct'    => ['nullable', 'numeric', 'min:0', 'max:100'],
            'disc_amt'    => ['nullable', 'integer', 'min:0'],
            'total'       => ['required', 'integer', 'min:0'],
            'currency'    => ['nullable', 'string', 'max:10'],
            'status'      => ['nullable', 'string', 'max:50'],
            'term'        => ['nullable', 'string', 'max:100'],
            'sales'       => ['nullable', 'string', 'max:100'],
            'contract_no' => ['nullable', 'string', 'max:100'],
            'doc_type'    => ['nullable', 'string', 'max:100'],
        ]);

        $this->store->create($request->only('date','warehouse','customer_id','name','area','wa','note','disc_pct','disc_amt','total','currency','status','term','sales','contract_no','doc_type'));
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
            'date'        => ['required', 'date'],
            'warehouse'   => ['required', 'string', 'max:100'],
            'customer_id' => ['required', 'string', 'max:50'],
            'name'        => ['required', 'string', 'max:200'],
            'area'        => ['nullable', 'string', 'max:100'],
            'wa'          => ['nullable', 'string', 'max:50'],
            'note'        => ['nullable', 'string'],
            'disc_pct'    => ['nullable', 'numeric', 'min:0', 'max:100'],
            'disc_amt'    => ['nullable', 'integer', 'min:0'],
            'total'       => ['required', 'integer', 'min:0'],
            'currency'    => ['nullable', 'string', 'max:10'],
            'status'      => ['nullable', 'string', 'max:50'],
            'term'        => ['nullable', 'string', 'max:100'],
            'sales'       => ['nullable', 'string', 'max:100'],
            'contract_no' => ['nullable', 'string', 'max:100'],
            'doc_type'    => ['nullable', 'string', 'max:100'],
        ]);

        $this->store->update($id, $request->only('date','warehouse','customer_id','name','area','wa','note','disc_pct','disc_amt','total','currency','status','term','sales','contract_no','doc_type'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}
