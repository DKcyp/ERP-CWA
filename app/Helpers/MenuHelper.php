<?php

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

function getMenus()
{
    $roleId = Auth::user()->role_id;

    return Menu::whereNull('main_menu')
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
        ->orderBy('sort')
        ->get();
}
