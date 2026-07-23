<?php

use App\Models\LogUser;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

if (!function_exists('getMenus')) {
    function getMenus()
    {
        return Menu::where('active', 1)
            ->whereNull('main_menu')
            ->with([
                'children.roles',
                'roles',
                'children.children.roles'
            ])
            ->orderBy('sort', 'asc')
            ->get();
    }
}

if (!function_exists('isMenuAllowed')) {
    function isMenuAllowed($menu)
    {
        $roleId = Auth::user()->role_id;

        // Jika menu memiliki role -> allowed
        if ($menu->roles->contains('id', $roleId)) {
            return true;
        }

        // Jika anak menu memiliki role -> parent juga harus tampil
        foreach ($menu->children as $child) {
            if (isMenuAllowed($child)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('addLogUser')) {
    function addLogUser($activity)
    {
        LogUser::create([
            'user_id' => auth()->user()->id,
            'activity' => $activity ?? null,
            'ip_address' => request()->ip(),
        ]);
    }
}

if (!function_exists('isMenuOrChildActive')) {
    function isMenuOrChildActive($menu)
    {
        $currentPath = request()->path(); // contoh: "min-max/barang-jadi"
        $menuPath = trim(parse_url($menu->url, PHP_URL_PATH), '/'); // buang query string

        if ($currentPath === $menuPath) {
            return true;
        }

        foreach ($menu->children ?? [] as $child) {
            if (isMenuOrChildActive($child)) {
                return true;
            }
        }

        return false;
    }

}
