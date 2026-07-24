<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class CustomerToolsController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('customer-tools');
        View::share('activeMenu', 'customer-tools');
    }

    public function index()
    {
        return view('master.customer-tools.index');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();
        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) =>
                stripos($i['tool_name'] ?? '', $q) !== false ||
                stripos($i['serial_number'] ?? '', $q) !== false
            );
        }
        if ($request->filled('filter_status')) {
            $s = $request->filter_status;
            if ($s !== 'all') $data = array_filter($data, fn($i) => ($i['status'] ?? '') === $s);
        }
        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('loan_date_fmt', fn($row) => $row['loan_date'] ? \Carbon\Carbon::parse($row['loan_date'])->format('d/m/Y') : '-')
            ->addColumn('status_badge', function ($row) {
                $map = ['Dipinjam' => 'bg-warning text-dark', 'Dikembalikan' => 'bg-success'];
                $s = $row['status'] ?? 'Dipinjam';
                $c = $map[$s] ?? 'bg-secondary';
                return '<span class="badge ' . $c . '">' . $s . '</span>';
            })
            ->addColumn('action', fn($row) => '<div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $row['id'] . '"><i class="bi bi-pencil"></i></button>
                <button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $row['id'] . '"><i class="bi bi-trash"></i></button>
            </div>')
            ->rawColumns(['status_badge', 'action'])->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'   => ['required', 'string', 'max:50'],
            'tool_name'     => ['required', 'string', 'max:200'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'qty'           => ['nullable', 'integer', 'min:0'],
            'condition'     => ['nullable', 'string', 'max:50'],
            'loan_date'     => ['nullable', 'date'],
            'status'        => ['required', 'string', 'in:Dipinjam,Dikembalikan'],
            'note'          => ['nullable', 'string'],
        ]);
        $this->store->create($request->only('customer_id', 'tool_name', 'serial_number', 'qty', 'condition', 'loan_date', 'status', 'note'));
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    public function show($id)
    {
        $data = $this->store->find($id);
        if (!$data) return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id'   => ['required', 'string', 'max:50'],
            'tool_name'     => ['required', 'string', 'max:200'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'qty'           => ['nullable', 'integer', 'min:0'],
            'condition'     => ['nullable', 'string', 'max:50'],
            'loan_date'     => ['nullable', 'date'],
            'status'        => ['required', 'string', 'in:Dipinjam,Dikembalikan'],
            'note'          => ['nullable', 'string'],
        ]);
        $this->store->update($id, $request->only('customer_id', 'tool_name', 'serial_number', 'qty', 'condition', 'loan_date', 'status', 'note'));
        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}
