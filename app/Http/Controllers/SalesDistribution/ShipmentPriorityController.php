<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ShipmentPriorityController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('shipment-priorities');
        View::share('activeMenu', 'shipment-priority');
    }

    public function index()
    {
        return view('Sales-distribution.shipment-priority.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['priority_no'] ?? '', $q) !== false ||
                stripos($i['invoice_no'] ?? '', $q) !== false ||
                stripos($i['so_no'] ?? '', $q) !== false ||
                stripos($i['customer_id'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_status')) {
            $s = $request->filter_status;
            if ($s !== 'all') $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $s);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('promised_date_fmt', fn($r) => $r['promised_date'] ? \Carbon\Carbon::parse($r['promised_date'])->format('d/m/Y') : '-')
            ->addColumn('weight_fmt', fn($r) => number_format((float)($r['total_weight_volume'] ?? 0), 1, ',', '.'))
            ->addColumn('status_badge', function ($row) {
                $map = [
                    'HIGH'   => ['class' => 'bg-danger',  'label' => 'High'],
                    'MEDIUM' => ['class' => 'bg-warning text-dark', 'label' => 'Medium'],
                    'LOW'    => ['class' => 'bg-info text-dark', 'label' => 'Low'],
                    'DONE'   => ['class' => 'bg-success', 'label' => 'Done'],
                ];
                $s = $row['status'] ?? 'LOW';
                $c = $map[$s]['class'] ?? 'bg-secondary';
                $l = $map[$s]['label'] ?? $s;
                return '<span class="badge ' . $c . '">' . $l . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $row['id'] . '"><i class="bi bi-pencil"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['status_badge','action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'priority_no'        => ['required', 'string', 'max:50'],
            'invoice_no'         => ['required', 'string', 'max:50'],
            'so_no'              => ['required', 'string', 'max:50'],
            'customer_id'        => ['required', 'string', 'max:50'],
            'area'               => ['nullable', 'string', 'max:100'],
            'total_weight_volume'=> ['required', 'numeric', 'min:0'],
            'promised_date'      => ['required', 'date'],
            'status'             => ['nullable', 'string', 'max:50'],
        ]);
        $this->store->create($request->only('priority_no','invoice_no','so_no','customer_id','area','total_weight_volume','promised_date','status'));
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
            'priority_no'        => ['required', 'string', 'max:50'],
            'invoice_no'         => ['required', 'string', 'max:50'],
            'so_no'              => ['required', 'string', 'max:50'],
            'customer_id'        => ['required', 'string', 'max:50'],
            'area'               => ['nullable', 'string', 'max:100'],
            'total_weight_volume'=> ['required', 'numeric', 'min:0'],
            'promised_date'      => ['required', 'date'],
            'status'             => ['nullable', 'string', 'max:50'],
        ]);
        $this->store->update($id, $request->only('priority_no','invoice_no','so_no','customer_id','area','total_weight_volume','promised_date','status'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}