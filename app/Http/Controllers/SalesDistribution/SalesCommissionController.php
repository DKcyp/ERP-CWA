<?php

namespace App\Http\Controllers\SalesDistribution;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SalesCommissionController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('sales-commission');
        View::share('activeMenu', 'sales-commission');
    }

    public function index()
    {
        return view('Sales-distribution.sales-commission.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['comm_no'] ?? '', $q) !== false ||
                stripos($i['salesman_id'] ?? '', $q) !== false ||
                stripos($i['period'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_status')) {
            $s = $request->filter_status;
            if ($s !== 'all') $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $s);
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('date_fmt', fn($r) => $r['date'] ? \Carbon\Carbon::parse($r['date'])->format('d/m/Y') : '-')
            ->addColumn('target_fmt', fn($r) => 'Rp ' . number_format((int)($r['target_amount'] ?? 0), 0, ',', '.'))
            ->addColumn('achieved_fmt', fn($r) => 'Rp ' . number_format((int)($r['achieved_amount'] ?? 0), 0, ',', '.'))
            ->addColumn('rate_fmt', fn($r) => ($r['commission_rate'] ?? 0) . '%')
            ->addColumn('total_commission_fmt', fn($r) => 'Rp ' . number_format((int)($r['total_commission_paid'] ?? 0), 0, ',', '.'))
            ->addColumn('status_badge', function ($row) {
                $map = ['DRAFT'=>'bg-secondary','CALCULATED'=>'bg-primary','APPROVED'=>'bg-success','PAID'=>'bg-info text-dark','REJECTED'=>'bg-danger'];
                $s = $row['status'] ?? 'DRAFT';
                return '<span class="badge ' . ($map[$s]??'bg-secondary') . '">' . $s . '</span>';
            })
            ->addColumn('calculation_base_badge', function ($row) {
                $b = $row['calculation_base'] ?? 'Omset';
                $c = $b === 'Pelunasan' ? 'bg-warning text-dark' : 'bg-light text-dark';
                return '<span class="badge ' . $c . ' border">' . $b . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $row['id'] . '"><i class="bi bi-pencil"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['status_badge','calculation_base_badge','action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'comm_no'              => ['required','string','max:50'],
            'date'                 => ['required','date'],
            'period'               => ['required','string','max:10'],
            'salesman_id'          => ['required','string','max:50'],
            'calculation_base'     => ['nullable','string','max:50'],
            'target_amount'        => ['required','numeric','min:0'],
            'achieved_amount'      => ['required','numeric','min:0'],
            'commission_rate'      => ['required','numeric','min:0','max:100'],
            'total_commission_paid'=> ['required','numeric','min:0'],
            'status'               => ['nullable','string','max:50'],
        ]);
        $this->store->create($request->only('comm_no','date','period','salesman_id','calculation_base','target_amount','achieved_amount','commission_rate','total_commission_paid','status'));
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
            'comm_no'              => ['required','string','max:50'],
            'date'                 => ['required','date'],
            'period'               => ['required','string','max:10'],
            'salesman_id'          => ['required','string','max:50'],
            'calculation_base'     => ['nullable','string','max:50'],
            'target_amount'        => ['required','numeric','min:0'],
            'achieved_amount'      => ['required','numeric','min:0'],
            'commission_rate'      => ['required','numeric','min:0','max:100'],
            'total_commission_paid'=> ['required','numeric','min:0'],
            'status'               => ['nullable','string','max:50'],
        ]);
        $this->store->update($id, $request->only('comm_no','date','period','salesman_id','calculation_base','target_amount','achieved_amount','commission_rate','total_commission_paid','status'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}