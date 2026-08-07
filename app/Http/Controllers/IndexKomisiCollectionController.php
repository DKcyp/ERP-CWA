<?php

namespace App\Http\Controllers;

use App\Services\DummyStore;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use View;

class IndexKomisiCollectionController extends Controller
{
    protected DummyStore $store;

    public function __construct()
    {
        $this->store = new DummyStore('index-komisi-collection');
        $this->initDummyData();
        View::share('activeMenu', 'index-komisi-collection');
    }

    protected function initDummyData(): void
    {
        if (!empty($this->store->all())) return;

        $rules = [
            ['type' => 'Kolektibilitas 1', 'min' => 90, 'max' => 100, 'index_commission' => 1.5],
            ['type' => 'Kolektibilitas 1', 'min' => 80, 'max' => 89.99, 'index_commission' => 1.25],
            ['type' => 'Kolektibilitas 2', 'min' => 70, 'max' => 79.99, 'index_commission' => 1.0],
            ['type' => 'Kolektibilitas 2', 'min' => 60, 'max' => 69.99, 'index_commission' => 0.85],
            ['type' => 'Kolektibilitas 3', 'min' => 50, 'max' => 59.99, 'index_commission' => 0.7],
            ['type' => 'Kolektibilitas 3', 'min' => 40, 'max' => 49.99, 'index_commission' => 0.5],
            ['type' => 'Kolektibilitas 4', 'min' => 30, 'max' => 39.99, 'index_commission' => 0.3],
            ['type' => 'Kolektibilitas 4', 'min' => 0, 'max' => 29.99, 'index_commission' => 0.1],
        ];

        foreach ($rules as $r) {
            $this->store->create($r);
        }
    }

    public function index()
    {
        return view('index-komisi-collection');
    }

    public function table(Request $request)
    {
        $data = $this->store->all();

        if ($request->filled('filter_search')) {
            $q = $request->filter_search;
            $data = array_filter($data, fn($i) => stripos($i['type'] ?? '', $q) !== false);
        }

        $data = array_values($data);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('min_fmt', fn($r) => number_format($r['min'] ?? 0, 2, ',', '.').'%')
            ->addColumn('max_fmt', fn($r) => number_format($r['max'] ?? 0, 2, ',', '.').'%')
            ->addColumn('index_fmt', function ($r) {
                $val = $r['index_commission'] ?? 0;
                $class = $val >= 1.0 ? 'text-success' : ($val >= 0.5 ? 'text-warning' : 'text-danger');
                return '<strong class="'.$class.'">'.$val.'</strong>';
            })
            ->addColumn('action', function ($r) {
                return '<div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" onclick="editRecord(\''.$r['id'].'\')" title="Edit"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-outline-danger" onclick="deleteRecord(\''.$r['id'].'\')" title="Hapus"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['min_fmt','max_fmt','index_fmt','action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:100',
            'min' => 'required|numeric|min:0|max:100',
            'max' => 'required|numeric|min:0|max:100',
            'index_commission' => 'required|numeric|min:0|max:10',
        ]);

        $this->store->create($request->only(['type','min','max','index_commission']));
        return response()->json(['success' => true, 'message' => 'Data berhasil disimpan.']);
    }

    public function show($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);

        $request->validate([
            'type' => 'required|string|max:100',
            'min' => 'required|numeric|min:0|max:100',
            'max' => 'required|numeric|min:0|max:100',
            'index_commission' => 'required|numeric|min:0|max:10',
        ]);

        $this->store->update($id, $request->only(['type','min','max','index_commission']));
        return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        $item = $this->store->find($id);
        if (!$item) return response()->json(['error' => 'Data tidak ditemukan'], 404);
        $this->store->delete($id);
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
    }
}
