<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $roleId = Auth::user()->role_id;

        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $menus = Menu::whereNull('main_menu')
            ->where('active', 1)
            ->whereHas('roles', function ($q) use ($roleId) {
                $q->where('role_id', $roleId);
            })
            ->with(['children' => function ($q) use ($roleId) {
                $q->where('active', 1)
                    ->whereHas('roles', function ($qq) use ($roleId) {
                        $qq->where('role_id', $roleId);
                    });
            }])
            ->get();

        $results = [];
        $this->collectMenus($menus, $query, $results);

        return response()->json($results);
    }

    private function collectMenus($menus, $query, &$results)
    {
        foreach ($menus as $menu) {
            // Check parent menu
            if (stripos($menu->name, $query) !== false) {
                $results[] = [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'url' => $menu->url,
                    'icon' => $menu->icon,
                    'level' => 'Parent'
                ];
            }

            // Check children
            if ($menu->children->isNotEmpty()) {
                $this->collectChildren($menu->children, $query, $results);
            }
        }
    }

    private function collectChildren($children, $query, &$results)
    {
        foreach ($children as $child) {
            if (stripos($child->name, $query) !== false) {
                $results[] = [
                    'id' => $child->id,
                    'name' => $child->name,
                    'url' => $child->url,
                    'icon' => $child->icon,
                    'level' => 'Child'
                ];
            }

            // Check grandchildren recursively
            if ($child->children && $child->children->isNotEmpty()) {
                $this->collectChildren($child->children, $query, $results);
            }
        }
    }
}
