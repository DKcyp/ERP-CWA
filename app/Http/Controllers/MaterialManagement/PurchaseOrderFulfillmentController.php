<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class PurchaseOrderFulfillmentController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('purchase-orders');
        View::share('activeMenu', 'purchase-fulfillment-report');
    }

    public function index()
    {
        return view('material-management.purchase-order-fulfillment.index');
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
                stripos($i['po_number'] ?? '', $q) !== false ||
                stripos($i['supplier_name'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('po_date_fmt', fn($row) => \Carbon\Carbon::parse($row['po_date'])->format('d/m/Y'))
            ->addColumn('total_items', fn($row) => count($row['items'] ?? []))
            ->addColumn('fulfilled_items', fn($row) => count(array_filter($row['items'] ?? [], fn($it) => ($it['qty_fulfilled'] ?? 0) >= ($it['qty'] ?? 0))))
            ->addColumn('progress_bar', function ($row) {
                $items = $row['items'] ?? [];
                $total = count($items);
                $done = count(array_filter($items, fn($it) => ($it['qty_fulfilled'] ?? 0) >= ($it['qty'] ?? 0)));
                $pct = $total > 0 ? round(($done / $total) * 100) : 0;
                $color = $pct == 100 ? 'bg-success' : ($pct >= 50 ? 'bg-warning' : 'bg-danger');
                return '<div class="progress" style="height:20px; min-width:120px;">
                    <div class="progress-bar ' . $color . ' fw-semibold" style="width:' . $pct . '%">' . $pct . '%</div>
                </div>';
            })
            ->addColumn('total_amount', function ($row) {
                $items = $row['items'] ?? [];
                $total = array_sum(array_map(fn($i) => ($i['qty'] ?? 0) * ($i['price'] ?? 0), $items));
                return 'Rp ' . number_format($total, 0, ',', '.');
            })
            ->addColumn('status_badge', function ($row) {
                $map = [
                    'DRAFT'     => ['class' => 'bg-secondary',          'label' => 'Draft'],
                    'PENDING'   => ['class' => 'bg-warning text-dark',  'label' => 'Pending'],
                    'APPROVED'  => ['class' => 'bg-info text-dark',     'label' => 'Approved'],
                    'REJECTED'  => ['class' => 'bg-danger',             'label' => 'Rejected'],
                    'FULFILLED' => ['class' => 'bg-success',            'label' => 'Fulfilled'],
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
            ->rawColumns(['progress_bar', 'status_badge', 'action'])
            ->make(true);
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
