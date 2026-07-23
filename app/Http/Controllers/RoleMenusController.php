<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Role;
use App\Models\RoleMenu;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class RoleMenusController extends Controller
{
    public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::select('*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a onclick="onShowRole(`' . $row->id . '`)" class="btn btn-primary btn-sm me-2">
                              <i class="fas fa-user-shield"></i>&nbsp;&nbsp;Hak Akses Menu
                            </a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function index()
    {
        $menus = Menu::whereNull('main_menu')
            ->with('children') 
            ->orderBy('sort')
            ->get();
        $menuOptions = MenuController::getMenuOptions($menus); 


        return view('role-menu.index', compact('menus','menuOptions'));
    }

    public function show(Request $request)
    {
        $roleId = $request->role_id;

        $flat = \App\Models\Menu::query()
            ->leftJoin('role_menus as rm', function ($j) use ($roleId) {
                $j->on('rm.menu_id', '=', 'menus.id')->where('rm.role_id', '=', $roleId);
            })
            ->select(
                'menus.id',
                'menus.name',
                'menus.main_menu',   // parent_id (nullable)
                'menus.menu_hassub', // 0/1
                \DB::raw('CASE WHEN rm.menu_id IS NULL THEN 0 ELSE 1 END AS menu_selected')
            )
            // Urutkan: parent (NULL) dulu, lalu group per parent, lalu alfabetis name
            ->orderByRaw('CASE WHEN menus.main_menu IS NULL THEN 0 ELSE 1 END')
            ->orderBy('menus.main_menu')
            ->orderBy('menus.name')
            ->get();

        return response()->json(['menu' => $flat]);
    }


    public function store(Request $request)
    {
        $data = $request->all();
        $data['role_code'] = str_replace(' ', '', $request->role_name);

        Role::updateOrCreate(
            ['id' => $request->role_id],
            $data
        );

        return response()->json(['success' => 'Role saved successfully.']);
    }

    public function showRole(Request $request)
    {
        try {
            $data = Role::findOrFail($request->role_id);
            return response()->json([
                'code' => 200,
                'success' => true,
                'message' => "Successfully get data!",
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 404,
                'success' => false,
                'message' => $th->getMessage()
            ], 404);
        }
    }

    public function saveRoleMenu(Request $request)
    {
        try {
            RoleMenu::where('role_id', $request->role_id)->delete();
            $ids = (array) $request->menu_id; // bisa null
            foreach ($ids as $menu_id) {
                RoleMenu::create([
                    'role_id' => $request->role_id,
                    'menu_id' => $menu_id
                ]);
            }
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        $data = Role::findOrFail($request->id);
        $data->delete();
        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => "Deleted data successfully"
        ]);
    }
}
