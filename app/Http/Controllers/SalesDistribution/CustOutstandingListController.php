<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class CustOutstandingListController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('cust-outstanding-list');
        View::share('activeMenu', 'cust-outstanding-list');
    }

    public function index()
    {
        return view('Sales-distribution.cust-outstanding-list.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['invoice_no'] ?? '', $q) !== false ||
                stripos($i['customer_id'] ?? '', $q) !== false ||
                stripos($i['customer_name'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('due_date_fmt', fn($r) => $r['due_date'] ? \Carbon\Carbon::parse($r['due_date'])->format('d/m/Y') : '-')
            ->addColumn('total_fmt', fn($r) => 'Rp ' . number_format((int)($r['total'] ?? 0), 0, ',', '.'))
            ->addColumn('outstanding_fmt', fn($r) => 'Rp ' . number_format((int)($r['outstanding'] ?? 0), 0, ',', '.'))
            ->addColumn('age_badge', function ($row) {
                $age = (int)($row['age_days'] ?? 0);
                if ($age <= 30) $cls = 'bg-success';
                elseif ($age <= 60) $cls = 'bg-warning text-dark';
                elseif ($age <= 90) $cls = 'bg-orange';
                else $cls = 'bg-danger';
                return '<span class="badge ' . $cls . '">' . $age . ' hari</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-detail" data-id="' . $row['id'] . '"><i class="bi bi-eye"></i></button>
                </div>';
            })
            ->rawColumns(['age_badge','action'])->make(true);
    }

    public function show($id)
    {
        $d = $this->store->find($id);
        return $d ? response()->json(['success'=>true,'data'=>$d]) : response()->json(['message'=>'Data tidak ditemukan.'],404);
    }
}