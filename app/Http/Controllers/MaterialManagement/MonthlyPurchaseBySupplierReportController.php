<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class MonthlyPurchaseBySupplierReportController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('purchase-invoices');
        View::share('activeMenu', 'monthly-purchase-by-supplier-report');
    }

    public function index()
    {
        return view('material-management.monthly-purchase-by-supplier-report.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        $month = $request->input('month', date('m'));
        $year  = $request->input('year', date('Y'));

        $data = array_filter($data, function ($item) use ($month, $year) {
            $date = $item['invoice_date'] ?? '';
            if (!$date) return false;
            [$y, $m] = explode('-', $date);
            return $y == $year && $m == $month;
        });

        $grouped = [];
        foreach ($data as $inv) {
            $supplier = $inv['supplier_name'] ?? 'Unknown';
            if (!isset($grouped[$supplier])) {
                $grouped[$supplier] = [
                    'supplier_name'  => $supplier,
                    'total_invoices' => 0,
                    'total_items'    => 0,
                    'total_amount'   => 0,
                    'statuses'       => [],
                ];
            }
            $grouped[$supplier]['total_invoices']++;
            $grouped[$supplier]['total_items'] += count($inv['items'] ?? []);
            $items = $inv['items'] ?? [];
            $grouped[$supplier]['total_amount'] += array_sum(array_map(fn($i) => ($i['qty'] ?? 0) * ($i['price'] ?? 0), $items));
            $s = $inv['status'] ?? 'DRAFT';
            $grouped[$supplier]['statuses'][$s] = ($grouped[$supplier]['statuses'][$s] ?? 0) + 1;
        }

        return DataTables::of(array_values($grouped))
            ->addIndexColumn()
            ->addColumn('total_amount_fmt', function ($row) {
                return 'Rp ' . number_format($row['total_amount'], 0, ',', '.');
            })
            ->addColumn('status_summary', function ($row) {
                $map = [
                    'DRAFT'     => 'bg-secondary',
                    'PENDING'   => 'bg-warning text-dark',
                    'APPROVED'  => 'bg-info text-dark',
                    'REJECTED'  => 'bg-danger',
                    'PAID'      => 'bg-success',
                ];
                $html = '';
                foreach ($row['statuses'] as $s => $cnt) {
                    $color = $map[$s] ?? 'bg-secondary';
                    $html .= '<span class="badge ' . $color . ' me-1">' . $s . ': ' . $cnt . '</span>';
                }
                return $html ?: '-';
            })
            ->rawColumns(['status_summary'])
            ->make(true);
    }

    public function summary(Request $request)
    {
        $data = $this->store->all();

        $month = $request->input('month', date('m'));
        $year  = $request->input('year', date('Y'));

        $data = array_filter($data, function ($item) use ($month, $year) {
            $date = $item['invoice_date'] ?? '';
            if (!$date) return false;
            [$y, $m] = explode('-', $date);
            return $y == $year && $m == $month;
        });

        $totalSuppliers = count(array_unique(array_column($data, 'supplier_name')));
        $totalInvoices  = count($data);
        $totalAmount    = 0;
        foreach ($data as $inv) {
            $items = $inv['items'] ?? [];
            $totalAmount += array_sum(array_map(fn($i) => ($i['qty'] ?? 0) * ($i['price'] ?? 0), $items));
        }

        $monthName = \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');

        return response()->json([
            'success'         => true,
            'total_suppliers' => $totalSuppliers,
            'total_invoices'  => $totalInvoices,
            'total_amount'    => 'Rp ' . number_format($totalAmount, 0, ',', '.'),
            'period'          => $monthName,
        ]);
    }
}
