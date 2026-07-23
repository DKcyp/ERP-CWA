<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Menu;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Datatables;

class MenuController extends Controller
{
    public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = Menu::select('*');
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" class="edit-button edit btn btn-primary btn-sm">View </a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
public static function getMenuOptions($menus, $prefix = '')
{
    $options = [];

    foreach ($menus as $menu) {
        $options[] = [
            'id' => $menu->id,
            'name' => $prefix . $menu->name,
        ];

        if ($menu->children->isNotEmpty()) {
            $children = self::getMenuOptions($menu->children, $prefix . '-- ');
            $options = array_merge($options, $children);
        }
    }

    return $options;
}

    public function index()
    {
        $menus = Menu::whereNull('main_menu')
            ->with('children') 
            ->orderBy('sort')
            ->get();
        $menuOptions = MenuController::getMenuOptions($menus); 


        return view('menu.index', compact('menus','menuOptions'));
    }
    
public function sort(Request $request)
{
    $order = $request->input('order', []);

    if (!is_array($order)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Format data urutan tidak valid.'
        ], 422);
    }

    DB::transaction(function () use ($order) {
        $parentsWithChildren = [];

        foreach ($order as $item) {
            $id = $item['id'] ?? null;
            if (!$id) {
                continue;
            }

            $sort = (int) ($item['sort'] ?? 0);
            $parentId = $item['parent_id'] ?? null;

            Menu::where('id', $id)->update([
                'sort' => $sort,
                'main_menu' => $parentId,
            ]);

            if ($parentId) {
                $parentsWithChildren[$parentId] = true;
            }
        }

        Menu::query()->update(['menu_hassub' => 0]);

        if (!empty($parentsWithChildren)) {
            Menu::whereIn('id', array_keys($parentsWithChildren))->update(['menu_hassub' => 1]);
        }
    });

    return response()->json(['status' => 'success']);
}

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('menu::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $id = $request->input('id');

        $rules = [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('menus', 'code')->ignore($id),
            ],
            'name' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'main_menu' => ['nullable', 'exists:menus,id'],
            'sort' => 'required|integer',
            'active' => 'required|boolean',
        ];

        if ($id) {
            $rules['main_menu'][] = Rule::notIn([$id]);
        }

        $validated = $request->validate($rules);
        $parentId = $validated['main_menu'] ?? null;
        if ($parentId === '') {
            $parentId = null;
        }

        $payload = [
            'code' => $validated['code'],
            'name' => $validated['name'],
            'url' => $validated['url'] ?? null,
            'icon' => $validated['icon'] ?? null,
            'main_menu' => $parentId,
            'sort' => (int) $validated['sort'],
            'active' => (int) $validated['active'],
        ];

        $menu = DB::transaction(function () use ($id, $payload) {
            if ($id) {
                /** @var \App\Models\Menu $menu */
                $menu = Menu::findOrFail($id);
                $oldParent = $menu->main_menu;
                $menu->update($payload);
                $this->refreshParentFlag($oldParent);
            } else {
                $menu = Menu::create($payload + ['menu_hassub' => 0]);
            }

            $this->refreshParentFlag($menu->main_menu);

            return $menu;
        });

        return response()->json([
            'status' => 'success',
            'message' => $id ? 'Menu berhasil diperbarui.' : 'Menu berhasil ditambahkan.',
            'data' => $menu,
        ]);
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('menu::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('menu::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id');

        DB::transaction(function () use ($id) {
            $menu = Menu::findOrFail($id);
            $parentId = $menu->main_menu;
            $menu->delete();
            $this->refreshParentFlag($parentId);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Menu berhasil dihapus.',
        ]);
    }

    protected function refreshParentFlag(?string $parentId): void
    {
        if (!$parentId) {
            return;
        }

        $hasChildren = Menu::where('main_menu', $parentId)->exists();
        Menu::where('id', $parentId)->update(['menu_hassub' => $hasChildren ? 1 : 0]);
    }
}
