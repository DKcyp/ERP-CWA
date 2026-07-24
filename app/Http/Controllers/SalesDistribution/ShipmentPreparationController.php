<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class ShipmentPreparationController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('shipment-preparation');
        View::share('activeMenu', 'shipment-preparation');
    }

    public function index()
    {
        return view('Sales-distribution.shipment-preparation.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['prep_no'] ?? '', $q) !== false ||
                stripos($i['warehouse_id'] ?? '', $q) !== false ||
                stripos($i['route_area'] ?? '', $q) !== false ||
                stripos($i['fleet_type'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_status')) {
            $s = $request->filter_status;
            if ($s !== 'all') $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $s);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('total_weight_fmt', fn($r) => number_format((float)($r['total_weight'] ?? 0), 1, ',', '.') . ' kg')
            ->addColumn('total_volume_fmt', fn($r) => number_format((float)($r['total_volume'] ?? 0), 1, ',', '.') . ' m³')
            ->addColumn('status_badge', function ($row) {
                $map = ['PLANNING'=>'bg-secondary','LOADING'=>'bg-primary','DEPARTED'=>'bg-warning text-dark','ARRIVED'=>'bg-success','CANCELED'=>'bg-danger'];
                $s = $row['status'] ?? 'PLANNING';
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
            'prep_no'      => ['required','string','max:50'],
            'date'         => ['required','date'],
            'warehouse_id' => ['nullable','string','max:50'],
            'do_list'      => ['nullable','string'],
            'total_weight' => ['nullable','numeric','min:0'],
            'total_volume' => ['nullable','numeric','min:0'],
            'fleet_type'   => ['nullable','string','max:100'],
            'route_area'   => ['nullable','string','max:100'],
            'status'       => ['nullable','string','max:50'],
        ]);
        $this->store->create($request->only('prep_no','date','warehouse_id','do_list','total_weight','total_volume','fleet_type','route_area','status'));
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
            'prep_no'      => ['required','string','max:50'],
            'date'         => ['required','date'],
            'warehouse_id' => ['nullable','string','max:50'],
            'do_list'      => ['nullable','string'],
            'total_weight' => ['nullable','numeric','min:0'],
            'total_volume' => ['nullable','numeric','min:0'],
            'fleet_type'   => ['nullable','string','max:100'],
            'route_area'   => ['nullable','string','max:100'],
            'status'       => ['nullable','string','max:50'],
        ]);
        $this->store->update($id, $request->only('prep_no','date','warehouse_id','do_list','total_weight','total_volume','fleet_type','route_area','status'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}