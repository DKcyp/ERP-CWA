<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SalesInvoiceListController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('sales-invoices');
        View::share('activeMenu', 'sales-invoice-list');
    }

    public function index()
    {
        return view('Sales-distribution.sales-invoice-list.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['sales_order'] ?? '', $q) !== false ||
                stripos($i['customer_id'] ?? '', $q) !== false ||
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['no_faktur'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_status')) {
            $s = $request->filter_status;
            if ($s !== 'all') $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $s);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('due_date_fmt', fn($r) => $r['due_date'] ? \Carbon\Carbon::parse($r['due_date'])->format('d/m/Y') : '-')
            ->addColumn('total_fmt', fn($r) => number_format((int)($r['total'] ?? 0), 0, ',', '.'))
            ->addColumn('disc_amt_fmt', fn($r) => number_format((int)($r['disc_amt'] ?? 0), 0, ',', '.'))
            ->addColumn('outstanding_fmt', fn($r) => number_format((int)($r['outstanding'] ?? 0), 0, ',', '.'))
            ->addColumn('status_badge', function ($row) {
                $map = [
                    'DRAFT'    => ['class' => 'bg-secondary',  'label' => 'Draft'],
                    'SENT'     => ['class' => 'bg-info text-dark', 'label' => 'Sent'],
                    'PAID'     => ['class' => 'bg-success',    'label' => 'Paid'],
                    'OVERDUE'  => ['class' => 'bg-danger',     'label' => 'Overdue'],
                    'CANCELED' => ['class' => 'bg-secondary',  'label' => 'Canceled'],
                ];
                $s = $row['status'] ?? 'DRAFT';
                $c = $map[$s]['class'] ?? 'bg-secondary';
                $l = $map[$s]['label'] ?? $s;
                return '<span class="badge ' . $c . '">' . $l . '</span>';
            })
            ->addColumn('delivery_badge', function ($row) {
                $map = ['PENDING' => 'bg-secondary','PARTIAL' => 'bg-warning text-dark','FULL' => 'bg-success'];
                $s = $row['delivery_status'] ?? 'PENDING';
                $c = $map[$s] ?? 'bg-secondary';
                return '<span class="badge ' . $c . '">' . $s . '</span>';
            })
            ->addColumn('printed_badge', function ($row) {
                $v = $row['printed_status'] ?? 'N';
                return $v === 'Y' ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $row['id'] . '"><i class="bi bi-pencil"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['status_badge','delivery_badge','printed_badge','action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'           => ['required', 'date'],
            'due_date'       => ['required', 'date'],
            'doc_type'       => ['nullable', 'string', 'max:50'],
            'printed_status' => ['nullable', 'string', 'max:10'],
            'purchase_note'  => ['nullable', 'string'],
            'warehouse'      => ['required', 'string', 'max:100'],
            'sales_order'    => ['nullable', 'string', 'max:50'],
            'no_faktur'      => ['nullable', 'string', 'max:50'],
            'customer_id'    => ['required', 'string', 'max:50'],
            'name'           => ['required', 'string', 'max:200'],
            'area'           => ['nullable', 'string', 'max:100'],
            'wa'             => ['nullable', 'string', 'max:50'],
            'note'           => ['nullable', 'string'],
            'curr'           => ['nullable', 'string', 'max:10'],
            'total'          => ['required', 'integer', 'min:0'],
            'disc_pct'       => ['nullable', 'numeric', 'min:0', 'max:100'],
            'disc_amt'       => ['nullable', 'integer', 'min:0'],
            'status'         => ['nullable', 'string', 'max:50'],
            'term'           => ['nullable', 'string', 'max:100'],
            'user'           => ['nullable', 'string', 'max:100'],
            'outstanding'    => ['nullable', 'integer', 'min:0'],
            'delivery_status'=> ['nullable', 'string', 'max:50'],
        ]);
        $this->store->create($request->only('date','due_date','doc_type','printed_status','purchase_note','warehouse','sales_order','no_faktur','customer_id','name','area','wa','note','curr','total','disc_pct','disc_amt','status','term','user','outstanding','delivery_status'));
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
            'date'           => ['required', 'date'],
            'due_date'       => ['required', 'date'],
            'doc_type'       => ['nullable', 'string', 'max:50'],
            'printed_status' => ['nullable', 'string', 'max:10'],
            'purchase_note'  => ['nullable', 'string'],
            'warehouse'      => ['required', 'string', 'max:100'],
            'sales_order'    => ['nullable', 'string', 'max:50'],
            'no_faktur'      => ['nullable', 'string', 'max:50'],
            'customer_id'    => ['required', 'string', 'max:50'],
            'name'           => ['required', 'string', 'max:200'],
            'area'           => ['nullable', 'string', 'max:100'],
            'wa'             => ['nullable', 'string', 'max:50'],
            'note'           => ['nullable', 'string'],
            'curr'           => ['nullable', 'string', 'max:10'],
            'total'          => ['required', 'integer', 'min:0'],
            'disc_pct'       => ['nullable', 'numeric', 'min:0', 'max:100'],
            'disc_amt'       => ['nullable', 'integer', 'min:0'],
            'status'         => ['nullable', 'string', 'max:50'],
            'term'           => ['nullable', 'string', 'max:100'],
            'user'           => ['nullable', 'string', 'max:100'],
            'outstanding'    => ['nullable', 'integer', 'min:0'],
            'delivery_status'=> ['nullable', 'string', 'max:50'],
        ]);
        $this->store->update($id, $request->only('date','due_date','doc_type','printed_status','purchase_note','warehouse','sales_order','no_faktur','customer_id','name','area','wa','note','curr','total','disc_pct','disc_amt','status','term','user','outstanding','delivery_status'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}