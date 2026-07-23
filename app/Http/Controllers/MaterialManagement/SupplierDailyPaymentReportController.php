<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SupplierDailyPaymentReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('supplier-payments');
        View::share('activeMenu', 'daily-supplier-payment-report');
    }

    public function index()
    {
        return view('material-management.supplier-daily-payment-report.index');
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
            ->addColumn('total_paid_fmt', function ($row) {
                $total = (int)($row['total_paid'] ?? 0);
                return 'Rp ' . number_format($total, 0, ',', '.');
            })
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
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-detail" data-id="' . $row['id'] . '"><i class="bi bi-eye"></i></button>
                </div>';
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    public function summary(Request $request)
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

        $totalPayments = count($data);
        $totalAmount   = 0;
        $statusCounts  = [];

        foreach ($data as $p) {
            $totalAmount += (int)($p['total_paid'] ?? 0);
            $s = $p['status'] ?? 'DRAFT';
            $statusCounts[$s] = ($statusCounts[$s] ?? 0) + 1;
        }

        return response()->json([
            'success'         => true,
            'total_payments'  => $totalPayments,
            'total_amount'    => 'Rp ' . number_format($totalAmount, 0, ',', '.'),
            'total_amount_raw' => $totalAmount,
            'status_counts'   => $statusCounts,
        ]);
    }

    public function show($id)
    {
        $item = $this->store->find($id);
        if (!$item) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }
        return response()->json(['success' => true, 'data' => $item]);
    }
}
