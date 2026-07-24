<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class InvoicePaymentReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('invoice-payment-reports');
        View::share('activeMenu', 'invoice-payment-report');
    }

    public function index()
    {
        return view('Sales-distribution.invoice-payment-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['invoice_no'] ?? '', $q) !== false ||
                stripos($i['customer_name'] ?? '', $q) !== false ||
                stripos($i['status'] ?? '', $q) !== false
            );
        }

        if ($request->filled('filter_status')) {
            $s = $request->filter_status;
            $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $s);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('total_invoice_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_invoice'] ?? 0), 0, ',', '.'))
            ->addColumn('total_paid_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_paid'] ?? 0), 0, ',', '.'))
            ->addColumn('balance_due_fmt', fn($r) => 'Rp ' . number_format((int)($r['balance_due'] ?? 0), 0, ',', '.'))
            ->addColumn('status_badge', function ($row) {
                $map = ['LUNAS'=>'success','BELUM LUNAS'=>'warning','OVERDUE'=>'danger'];
                $cls = $map[$row['status'] ?? ''] ?? 'secondary';
                return '<span class="badge bg-' . $cls . '">' . ($row['status'] ?? '-') . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-detail" data-id="' . $row['id'] . '"><i class="bi bi-eye"></i></button>
                </div>';
            })
            ->rawColumns(['status_badge','action'])->make(true);
    }

    public function show($id)
    {
        $d = $this->store->find($id);
        return $d ? response()->json(['success'=>true,'data'=>$d]) : response()->json(['message'=>'Data tidak ditemukan.'],404);
    }
}