<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Menu;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RolePermisionController extends Controller
{
    public function __construct()
    {
        view()->share('activeMenu', 'role-permision');
    }

    public function index()
    {
        return view('role-permission.index');
    }

    public function table(Request $request)
    {
        $data = Role::select('*');

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('menu_count', function ($row) {
                return $row->menus()->count();
            })
            ->addColumn('action', function ($row) {
                return '<div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" onclick="editRole(`'.$row->id.'`)" title="Edit"><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-outline-info" onclick="detailRole(`'.$row->id.'`)" title="Lihat Hak Akses"><i class="bi bi-key"></i></button>
                    <button class="btn btn-outline-danger" onclick="deleteRole(`'.$row->id.'`)" title="Hapus"><i class="bi bi-trash"></i></button>
                </div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show(Request $request)
    {
        $role = Role::with('menus')->find($request->role_id);
        return response()->json(['success' => true, 'data' => $role]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:100',
        ]);

        $role = Role::updateOrCreate(
            ['id' => $request->role_id],
            [
                'role_name' => $request->role_name,
                'role_code' => str_replace(' ', '', $request->role_name),
            ]
        );

        return response()->json(['success' => true, 'message' => 'Role berhasil disimpan.']);
    }

    public function destroy(Request $request)
    {
        $role = Role::findOrFail($request->id);
        $role->menus()->detach();
        $role->delete();
        return response()->json(['success' => true, 'message' => 'Role berhasil dihapus.']);
    }

    public function getMenus()
    {
        $menus = Menu::orderBy('sort')->get();
        return response()->json(['success' => true, 'data' => $menus]);
    }

    public function savePermission(Request $request)
    {
        $role = Role::findOrFail($request->role_id);
        $role->menus()->sync((array) $request->menu_ids);
        return response()->json(['success' => true, 'message' => 'Hak akses berhasil disimpan.']);
    }
}
