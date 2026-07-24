<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SalesOrderFulfilmentController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('sales-order-fulfilments');
        View::share('activeMenu', 'sales-order-fulfilment');
    }

    public function index()
    {
        return view('Sales-distribution.sales-order-fulfilment.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['customer_id'] ?? '', $q) !== false ||
                stripos($i['name'] ?? '', $q) !== false ||
                stripos($i['sales_order'] ?? '', $q) !== false ||
                stripos($i['product_id'] ?? '', $q) !== false ||
                stripos($i['product_name'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_status')) {
            $s = $request->filter_status;
            if ($s !== 'all') $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $s);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('so_date_fmt', fn($r) => $r['so_date'] ? \Carbon\Carbon::parse($r['so_date'])->format('d/m/Y') : '-')
            ->addColumn('si_date_fmt', fn($r) => $r['si_date'] ? \Carbon\Carbon::parse($r['si_date'])->format('d/m/Y') : '-')
            ->addColumn('qty_diff', function ($r) {
                $soQty = (int)($r['so_qty'] ?? 0);
                $siQty = (int)($r['si_qty'] ?? 0);
                return $soQty - $siQty;
            })
            ->addColumn('status_badge', function ($row) {
                $map = [
                    'FULL'    => ['class' => 'bg-success', 'label' => 'Full'],
                    'PARTIAL' => ['class' => 'bg-warning text-dark', 'label' => 'Partial'],
                    'PENDING' => ['class' => 'bg-secondary', 'label' => 'Pending'],
                ];
                $s = $row['status'] ?? 'PENDING';
                $c = $map[$s]['class'] ?? 'bg-secondary';
                $l = $map[$s]['label'] ?? $s;
                return '<span class="badge ' . $c . '">' . $l . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-detail" data-id="' . $row['id'] . '"><i class="bi bi-eye"></i></button>
                </div>';
            })
            ->rawColumns(['status_badge', 'action'])->make(true);
    }

    public function show($id)
    {
        $d = $this->store->find($id);
        return $d ? response()->json(['success'=>true,'data'=>$d]) : response()->json(['message'=>'Data tidak ditemukan.'],404);
    }
}
