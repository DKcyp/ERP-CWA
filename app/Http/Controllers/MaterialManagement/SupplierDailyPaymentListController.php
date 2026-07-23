<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SupplierDailyPaymentListController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('supplier-payments');
        View::share('activeMenu', 'daily-supplier-payment-list');
    }

    public function index()
    {
        return view('material-management.supplier-daily-payment-list.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        if ($startDate || $endDate) {
            $data = array_filter($data, function ($item) use ($startDate, $endDate) {
                $date = $item['payment_date'] ?? '';
                if ($startDate && $date < $startDate) return false;
                if ($endDate && $date > $endDate) return false;
                return true;
            });
        }

        if ($request->filled('filter_status')) {
            $status = $request->filter_status;
            if ($status !== 'all') {
                $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $status);
            }
        }

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['payment_number'] ?? '', $q) !== false ||
                stripos($i['supplier_name'] ?? '', $q) !== false ||
                stripos($i['invoice_number'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('payment_date_fmt', fn($row) => \Carbon\Carbon::parse($row['payment_date'])->format('d/m/Y'))
            ->addColumn('invoice_date_fmt', fn($row) => !empty($row['invoice_date']) ? \Carbon\Carbon::parse($row['invoice_date'])->format('d/m/Y') : '-')
            ->addColumn('total_fmt', function ($row) {
                $val = (int)($row['total_paid'] ?? 0);
                return 'Rp ' . number_format($val, 0, ',', '.');
            })
            ->addColumn('subtotal_fmt', function ($row) {
                $val = (int)($row['subtotal'] ?? 0);
                return 'Rp ' . number_format($val, 0, ',', '.');
            })
            ->addColumn('discount_amount_fmt', function ($row) {
                $val = (int)($row['discount_amount'] ?? 0);
                return 'Rp ' . number_format($val, 0, ',', '.');
            })
            ->addColumn('lain_lain_fmt', function ($row) {
                $val = (int)($row['lain_lain'] ?? 0);
                return 'Rp ' . number_format($val, 0, ',', '.');
            })
            ->addColumn('total_payment_fmt', function ($row) {
                $val = (int)($row['total_payment'] ?? 0);
                return 'Rp ' . number_format($val, 0, ',', '.');
            })
            ->addColumn('payment_type_badge', function ($row) {
                $type = $row['payment_type'] ?? 'Regular';
                $class = $type === 'Down' ? 'bg-warning text-dark' : 'bg-primary';
                return '<span class="badge ' . $class . '">' . $type . '</span>';
            })
            ->addColumn('rate_fmt', fn($row) => number_format((int)($row['rate'] ?? 1), 0, ',', '.'))
            ->addColumn('discount_percent_fmt', fn($row) => ($row['discount_percent'] ?? 0) . '%')
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
            ->rawColumns(['status_badge', 'payment_type_badge'])
            ->make(true);
    }
}
