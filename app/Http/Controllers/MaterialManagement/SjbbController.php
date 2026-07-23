<?php

namespace App\Http\Controllers\MaterialManagement;

use App\Http\Controllers\Controller;
use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class SjbbController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('sjbb');
        View::share('activeMenu', 'sjbb');
    }

    public function index()
    {
        return view('material-management.sjbb.index');
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
                stripos($i['sjbb_number'] ?? '', $q) !== false ||
                stripos($i['supplier_name'] ?? '', $q) !== false ||
                stripos($i['notes'] ?? '', $q) !== false
            );
        }

        return DataTables::of(array_values($data))
            ->addIndexColumn()
            ->addColumn('sjbb_date_fmt', fn($row) => \Carbon\Carbon::parse($row['sjbb_date'])->format('d/m/Y'))
            ->addColumn('type_badge', function ($row) {
                $type = $row['type'] ?? 'IN';
                $class = $type === 'OUT' ? 'bg-danger' : 'bg-success';
                return '<span class="badge ' . $class . '">' . $type . '</span>';
            })
            ->addColumn('status_badge', function ($row) {
                $map = [
                    'DRAFT'     => ['class' => 'bg-secondary',          'label' => 'Draft'],
                    'APPROVED'  => ['class' => 'bg-info text-dark',     'label' => 'Approved'],
                    'COMPLETED' => ['class' => 'bg-success',            'label' => 'Completed'],
                ];
                $s = $row['status'] ?? 'DRAFT';
                $c = $map[$s]['class'] ?? 'bg-secondary';
                $l = $map[$s]['label'] ?? $s;
                return '<span class="badge ' . $c . '">' . $l . '</span>';
            })
            ->addColumn('action', function ($row) {
                $id = $row['id'];
                $status = $row['status'] ?? 'DRAFT';
                $btns = '<div class="btn-group btn-group-sm">';
                $btns .= '<button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $id . '"><i class="bi bi-pencil"></i></button>';
                if (in_array($status, ['DRAFT'])) {
                    $btns .= '<button type="button" class="btn btn-outline-success btn-approve" data-id="' . $id . '"><i class="bi bi-check-lg"></i></button>';
                }
                $btns .= '<button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $id . '"><i class="bi bi-trash"></i></button>';
                $btns .= '</div>';
                return $btns;
            })
            ->rawColumns(['type_badge', 'status_badge', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sjbb_number'  => ['required', 'string', 'max:50'],
            'sjbb_date'    => ['required', 'date'],
            'supplier_id'  => ['nullable', 'string', 'max:50'],
            'supplier_name' => ['required', 'string', 'max:200'],
            'type'         => ['required', 'string', 'in:IN,OUT'],
            'status'       => ['required', 'string', 'in:DRAFT,APPROVED,COMPLETED'],
            'notes'        => ['nullable', 'string'],
        ]);

        $this->store->create($request->only('sjbb_number', 'sjbb_date', 'supplier_id', 'supplier_name', 'type', 'status', 'notes'));

        return response()->json(['message' => 'Data berhasil disimpan.']);
    }

    public function show($id)
    {
        return response()->json([
            'success' => true,
            'data'    => $this->store->find($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sjbb_number'  => ['required', 'string', 'max:50'],
            'sjbb_date'    => ['required', 'date'],
            'supplier_id'  => ['nullable', 'string', 'max:50'],
            'supplier_name' => ['required', 'string', 'max:200'],
            'type'         => ['required', 'string', 'in:IN,OUT'],
            'status'       => ['required', 'string', 'in:DRAFT,APPROVED,COMPLETED'],
            'notes'        => ['nullable', 'string'],
        ]);

        $this->store->update($id, $request->only('sjbb_number', 'sjbb_date', 'supplier_id', 'supplier_name', 'type', 'status', 'notes'));

        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:APPROVED,COMPLETED'],
        ]);

        $this->store->update($id, [
            'status' => $request->input('status'),
        ]);

        return response()->json(['message' => 'Status berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $this->store->delete($id);

        return response()->json(['message' => 'Data berhasil dihapus.']);
    }
}
