<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class TandaTerimaPenagihanController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('tanda-terima-penagihan');
        View::share('activeMenu', 'tanda-terima-penagihan');
    }

    public function index()
    {
        return view('Sales-distribution.tanda-terima-penagihan.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['ttp_no'] ?? '', $q) !== false ||
                stripos($i['collector_name'] ?? '', $q) !== false ||
                stripos($i['customer_id'] ?? '', $q) !== false ||
                stripos($i['note'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_status')) {
            $s = $request->filter_status;
            if ($s !== 'all') $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $s);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('ttp_date_fmt', fn($r) => $r['ttp_date'] ? \Carbon\Carbon::parse($r['ttp_date'])->format('d/m/Y') : '-')
            ->addColumn('due_date_fmt', fn($r) => $r['due_date'] ? \Carbon\Carbon::parse($r['due_date'])->format('d/m/Y') : '-')
            ->addColumn('total_amount_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_amount'] ?? 0), 0, ',', '.'))
            ->addColumn('status_badge', function ($row) {
                $map = [
                    'DRAFT'     => ['class' => 'bg-secondary', 'label' => 'Draft'],
                    'SENT'      => ['class' => 'bg-primary',   'label' => 'Sent'],
                    'PARTIAL'   => ['class' => 'bg-warning text-dark', 'label' => 'Partial'],
                    'COLLECTED' => ['class' => 'bg-info text-dark', 'label' => 'Collected'],
                    'DONE'      => ['class' => 'bg-success',   'label' => 'Done'],
                    'CANCELED'  => ['class' => 'bg-danger',    'label' => 'Canceled'],
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
            ->rawColumns(['status_badge','action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ttp_no'          => ['required', 'string', 'max:50'],
            'ttp_date'        => ['required', 'date'],
            'collector_name'  => ['required', 'string', 'max:100'],
            'customer_id'     => ['required', 'string', 'max:50'],
            'total_inv_count' => ['required', 'integer', 'min:0'],
            'total_amount'    => ['required', 'numeric', 'min:0'],
            'due_date'        => ['required', 'date'],
            'status'          => ['nullable', 'string', 'max:50'],
            'note'            => ['nullable', 'string', 'max:500'],
        ]);
        $this->store->create($request->only('ttp_no','ttp_date','collector_name','customer_id','total_inv_count','total_amount','due_date','status','note'));
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
            'ttp_no'          => ['required', 'string', 'max:50'],
            'ttp_date'        => ['required', 'date'],
            'collector_name'  => ['required', 'string', 'max:100'],
            'customer_id'     => ['required', 'string', 'max:50'],
            'total_inv_count' => ['required', 'integer', 'min:0'],
            'total_amount'    => ['required', 'numeric', 'min:0'],
            'due_date'        => ['required', 'date'],
            'status'          => ['nullable', 'string', 'max:50'],
            'note'            => ['nullable', 'string', 'max:500'],
        ]);
        $this->store->update($id, $request->only('ttp_no','ttp_date','collector_name','customer_id','total_inv_count','total_amount','due_date','status','note'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}