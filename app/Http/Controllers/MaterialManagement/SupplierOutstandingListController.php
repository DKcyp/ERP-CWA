<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SupplierOutstandingListController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('purchase-invoices');
        View::share('activeMenu', 'supp-outstanding-list');
    }

    public function index()
    {
        return view('material-management.supplier-outstanding.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_status')) {
            $status = $request->filter_status;
            if ($status !== 'all') {
                $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $status);
            }
        }

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['invoice_number'] ?? '', $q) !== false ||
                stripos($i['supplier_name'] ?? '', $q) !== false ||
                stripos($i['note'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($row) => \Carbon\Carbon::parse($row['invoice_date'])->format('d/m/Y'))
            ->addColumn('due_date_fmt', fn($row) => \Carbon\Carbon::parse($row['due_date'])->format('d/m/Y'))
            ->addColumn('age_days', function ($row) {
                $due = \Carbon\Carbon::parse($row['due_date']);
                return $due->diffInDays(now(), false);
            })
            ->addColumn('total_fmt', function ($row) {
                $currency = $row['currency'] ?? 'IDR';
                $total = (int)($row['total'] ?? 0);
                $fmt = number_format($total, $currency === 'IDR' ? 0 : 2, ',', '.');
                return $currency === 'IDR' ? 'Rp ' . $fmt : $fmt;
            })
            ->addColumn('total_idr_fmt', fn($row) => 'Rp ' . number_format((int)($row['total'] ?? 0) * (int)($row['rate'] ?? 1), 0, ',', '.'))
            ->addColumn('outstanding_fmt', function ($row) {
                $total = (int)($row['total'] ?? 0);
                $paid = (int)($row['paid_amount'] ?? 0);
                $out = $total - $paid;
                $currency = $row['currency'] ?? 'IDR';
                $fmt = number_format($out, $currency === 'IDR' ? 0 : 2, ',', '.');
                $result = $currency === 'IDR' ? 'Rp ' . $fmt : $fmt;
                if ($out > 0) {
                    return '<span class="text-danger fw-semibold">' . $result . '</span>';
                }
                return '<span class="text-success">' . $result . '</span>';
            })
            ->addColumn('currency', fn($row) => $row['currency'] ?? 'IDR')
            ->addColumn('rate', fn($row) => number_format((int)($row['rate'] ?? 1), 0, ',', '.'))
            ->addColumn('status_badge', function ($row) {
                $map = [
                    'DRAFT'     => ['class' => 'bg-secondary',          'label' => 'Draft'],
                    'PENDING'   => ['class' => 'bg-warning text-dark',  'label' => 'Pending'],
                    'APPROVED'  => ['class' => 'bg-info text-dark',     'label' => 'Approved'],
                    'REJECTED'  => ['class' => 'bg-danger',             'label' => 'Rejected'],
                    'PAID'      => ['class' => 'bg-success',            'label' => 'Paid'],
                ];
                $s = $row['status'] ?? 'DRAFT';
                $c = $map[$s]['class'] ?? 'bg-secondary';
                $l = $map[$s]['label'] ?? $s;
                return '<span class="badge ' . $c . '">' . $l . '</span>';
            })
            ->rawColumns(['outstanding_fmt', 'status_badge'])
            ->make(true);
    }
}
