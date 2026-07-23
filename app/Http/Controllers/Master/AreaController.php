<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Lookup;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('master.area.index');
    }

    public function table(Request $request): JsonResponse
    {   
        $query = Area::with('kategori')->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('lookupdesc', function ($row) {
                // ambil lookupdesc dari relasi kategori
                return $row->kategori ? $row->kategori->lookupdesc : '';
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary btn-edit" data-id="' . $row->id . '"><i class="bi bi-pencil"></i></button>
                    <button type="button" class="btn btn-outline-danger btn-delete" data-id="' . $row->id . '"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // RULES
        $request->validate([
            'kategori_areaid'   => ['nullable', 'integer', 'exists:lookup,lookupvalue'],
            'nama_area'         => ['required', 'string', 'max:255'],
        ]);

        // Payload aman (hindari mass-assign semua input)
        $payload = [
            'kategori_id' => $request->kategori_areaid,
            'nama'        => $request->nama_area, // ← mapping ke kolom tabel
        ];

        Area::create($payload);

        return response()->json(['message' => 'Data Save Successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Area $area)
    {
        $area->load('kategori');

        return response()->json([
            'success' => true,
            'data' => $area
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area)
    {
        $area->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus'
        ]);
    }

    /**
     * Sumber Select2 untuk Roles (id = lookupvalue, text = lookupdesc).
     */
    public function getKategoriArea(Request $request)
    {
        $q = (string) $request->q;
        
        $data = Lookup::select('lookupvalue AS id', 'lookupdesc AS text')
            ->when($q !== '', function ($w) use ($q) {
                $w->where('lookupdesc', 'like', "%{$q}%");
            })
            ->where('lookupkey', 'kategori_area')
            ->get();

        return response()->json([
            'success' => true,
            'code'    => 200,
            'message' => "Get data successfully",
            'data'    => $data,
        ]);
    }
}
