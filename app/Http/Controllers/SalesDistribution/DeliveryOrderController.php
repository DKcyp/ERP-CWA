<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class DeliveryOrderController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('delivery-order');
        View::share('activeMenu', 'delivery-order');
    }

    public function index()
    {
        return view('Sales-distribution.delivery-order.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['do_no'] ?? '', $q) !== false ||
                stripos($i['so_no'] ?? '', $q) !== false ||
                stripos($i['si_no'] ?? '', $q) !== false ||
                stripos($i['customer_id'] ?? '', $q) !== false ||
                stripos($i['driver_name'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_status')) {
            $s = $request->filter_status;
            if ($s !== 'all') $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $s);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('status_badge', function ($row) {
                $map = ['DRAFT'=>'bg-secondary','LOADING'=>'bg-primary','DELIVERING'=>'bg-warning text-dark','DELIVERED'=>'bg-success','CANCELED'=>'bg-danger'];
                $s = $row['status'] ?? 'DRAFT';
                return '<span class="badge ' . ($map[$s]??'bg-secondary') . '">' . $s . '</span>';
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
            'do_no'            => ['required','string','max:50'],
            'date'             => ['required','date'],
            'so_no'            => ['nullable','string','max:50'],
            'si_no'            => ['nullable','string','max:50'],
            'warehouse_id'     => ['nullable','string','max:50'],
            'customer_id'      => ['required','string','max:50'],
            'driver_name'      => ['nullable','string','max:100'],
            'vehicle_no'       => ['nullable','string','max:30'],
            'delivery_address' => ['nullable','string','max:500'],
            'status'           => ['nullable','string','max:50'],
            'expeditor'        => ['nullable','string','max:100'],
        ]);
        $this->store->create($request->only('do_no','date','so_no','si_no','warehouse_id','customer_id','driver_name','vehicle_no','delivery_address','status','expeditor'));
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
            'do_no'            => ['required','string','max:50'],
            'date'             => ['required','date'],
            'so_no'            => ['nullable','string','max:50'],
            'si_no'            => ['nullable','string','max:50'],
            'warehouse_id'     => ['nullable','string','max:50'],
            'customer_id'      => ['required','string','max:50'],
            'driver_name'      => ['nullable','string','max:100'],
            'vehicle_no'       => ['nullable','string','max:30'],
            'delivery_address' => ['nullable','string','max:500'],
            'status'           => ['nullable','string','max:50'],
            'expeditor'        => ['nullable','string','max:100'],
        ]);
        $this->store->update($id, $request->only('do_no','date','so_no','si_no','warehouse_id','customer_id','driver_name','vehicle_no','delivery_address','status','expeditor'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}