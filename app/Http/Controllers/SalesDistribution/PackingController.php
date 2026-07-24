<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class PackingController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('packing');
        View::share('activeMenu', 'packing');
    }

    public function index()
    {
        return view('Sales-distribution.packing.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['packing_no'] ?? '', $q) !== false ||
                stripos($i['so_no'] ?? '', $q) !== false ||
                stripos($i['customer_id'] ?? '', $q) !== false ||
                stripos($i['packing_staff'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_status')) {
            $s = $request->filter_status;
            if ($s !== 'all') $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $s);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('weight_fmt', fn($r) => number_format((float)($r['weight'] ?? 0), 1, ',', '.'))
            ->addColumn('status_badge', function ($row) {
                $map = [
                    'DRAFT'   => ['class' => 'bg-secondary',  'label' => 'Draft'],
                    'PACKED'  => ['class' => 'bg-info text-dark', 'label' => 'Packed'],
                    'SHIPPED' => ['class' => 'bg-success',    'label' => 'Shipped'],
                    'CANCEL'  => ['class' => 'bg-danger',     'label' => 'Cancel'],
                ];
                $s = $row['status'] ?? 'DRAFT';
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
            ->rawColumns(['status_badge', 'action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'packing_no'    => ['required', 'string', 'max:50'],
            'date'          => ['required', 'date'],
            'so_no'         => ['required', 'string', 'max:50'],
            'customer_id'   => ['required', 'string', 'max:50'],
            'warehouse_id'  => ['required', 'string', 'max:50'],
            'packing_staff' => ['required', 'string', 'max:100'],
            'total_box'     => ['required', 'integer', 'min:1'],
            'weight'        => ['required', 'numeric', 'min:0'],
            'status'        => ['nullable', 'string', 'max:50'],
            'note'          => ['nullable', 'string'],
        ]);

        $this->store->create($request->only('packing_no','date','so_no','customer_id','warehouse_id','packing_staff','total_box','weight','status','note'));
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
            'packing_no'    => ['required', 'string', 'max:50'],
            'date'          => ['required', 'date'],
            'so_no'         => ['required', 'string', 'max:50'],
            'customer_id'   => ['required', 'string', 'max:50'],
            'warehouse_id'  => ['required', 'string', 'max:50'],
            'packing_staff' => ['required', 'string', 'max:100'],
            'total_box'     => ['required', 'integer', 'min:1'],
            'weight'        => ['required', 'numeric', 'min:0'],
            'status'        => ['nullable', 'string', 'max:50'],
            'note'          => ['nullable', 'string'],
        ]);

        $this->store->update($id, $request->only('packing_no','date','so_no','customer_id','warehouse_id','packing_staff','total_box','weight','status','note'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}
