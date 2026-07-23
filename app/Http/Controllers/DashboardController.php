<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'roles' => Role::count(),
            'menus' => Menu::count(),
            'configurations' => Configuration::count(),
        ];

        $recentUsers = User::with('roles')
            ->latest()
            ->take(5)
            ->get();

        $appConfig = Configuration::whereIn('config_code', [
                'app.name',
                'app.description',
                'app.logo',
            ])
            ->pluck('config_value', 'config_code');

        return view('dashboard', compact('stats', 'recentUsers', 'appConfig'));
    }
}
