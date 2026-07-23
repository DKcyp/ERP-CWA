<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SupplierBalanceSummaryController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('supplier-balance-summary');
        $this->initDummyData();
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $data = [
            [
                'supplier_code' => 'SUP-001',
                'supplier_name' => 'PT Maju Jaya',
                'total_invoice' => 250000000,
                'total_paid'    => 175000000,
                'balance'       => 75000000,
                'status'        => 'OUTSTANDING',
            ],
            [
                'supplier_code' => 'SUP-002',
                'supplier_name' => 'CV Bumi Sejahtera',
                'total_invoice' => 180000000,
                'total_paid'    => 180000000,
                'balance'       => 0,
                'status'        => 'PAID',
            ],
            [
                'supplier_code' => 'SUP-003',
                'supplier_name' => 'PT Abadi Makmur',
                'total_invoice' => 320000000,
                'total_paid'    => 100000000,
                'balance'       => 220000000,
                'status'        => 'OUTSTANDING',
            ],
            [
                'supplier_code' => 'SUP-004',
                'supplier_name' => 'CV Karya Mandiri',
                'total_invoice' => 95000000,
                'total_paid'    => 50000000,
                'balance'       => 45000000,
                'status'        => 'PARTIAL',
            ],
            [
                'supplier_code' => 'SUP-005',
                'supplier_name' => 'PT Sinar Abadi',
                'total_invoice' => 500000000,
                'total_paid'    => 500000000,
                'balance'       => 0,
                'status'        => 'PAID',
            ],
        ];

        foreach ($data as $item) {
            $this->store->create($item);
        }
    }

    public function index(): View
    {
        return view('master.supplier-balance-summary.index');
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
                stripos($i['supplier_code'] ?? '', $q) !== false ||
                stripos($i['supplier_name'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('total_invoice_fmt', fn($row) => 'Rp ' . number_format($row['total_invoice'] ?? 0, 0, ',', '.'))
            ->addColumn('total_paid_fmt', fn($row) => 'Rp ' . number_format($row['total_paid'] ?? 0, 0, ',', '.'))
            ->addColumn('balance_fmt', fn($row) => 'Rp ' . number_format($row['balance'] ?? 0, 0, ',', '.'))
            ->addColumn('status_badge', function ($row) {
                $map = [
                    'PAID'        => ['class' => 'bg-success', 'label' => 'Lunas'],
                    'PARTIAL'     => ['class' => 'bg-warning text-dark', 'label' => 'Angsuran'],
                    'OUTSTANDING' => ['class' => 'bg-danger', 'label' => 'Tertunggak'],
                ];
                $s = $row['status'] ?? 'OUTSTANDING';
                $c = $map[$s]['class'] ?? 'bg-secondary';
                $l = $map[$s]['label'] ?? $s;
                return '<span class="badge ' . $c . '">' . $l . '</span>';
            })
            ->rawColumns(['status_badge'])
            ->make(true);
    }
}
