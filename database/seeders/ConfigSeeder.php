<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use App\Models\RoleMenu;
use App\Models\Configuration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        // Create menu
        $dashboard = Menu::create([
            'name'          => 'Dashboard',
            'code'          => 'dashboard',
            'url'           => '/',
            'main_menu'     => null,
            'icon'          => 'bi bi-grid-fill'
        ]);

        $setting = Menu::create([
            'name'          => 'Setting',
            'code'          => 'settings',
            'url'           => 'setting',
            'main_menu'     => null,
            'icon'          => 'bi bi-gear',
            'menu_hassub'   => 1,
            'sort'          => 1
        ]);

        $master = Menu::create([
            'name'          => 'Master',
            'code'          => 'master',
            'url'           => 'master',
            'main_menu'     => null,
            'icon'          => 'bi bi-justify-left',
            'menu_hassub'   => 1,
            'sort'          => 2
        ]);


        // Create sub menu
        $users = Menu::create([
            'name'          => 'User',
            'code'          => 'users',
            'url'           => 'user',
            'main_menu'     => $setting->id,
            'icon'          => 'bi bi-circle',
            'sort'          => 0
        ]);

        $roles = Menu::create([
            'name'          => 'Role',
            'code'          => 'roles',
            'url'           => 'role-menu',
            'main_menu'     => $setting->id,
            'icon'          => 'bi bi-circle',
            'sort'          => 1
        ]);

        $configurations = Menu::create([
            'name'          => 'Config App',
            'code'          => 'configurations',
            'url'           => 'configuration',
            'main_menu'     => $setting->id,
            'icon'          => 'bi bi-circle',
            'sort'          => 2
        ]);

        $area = Menu::create([
            'name'          => 'Area',
            'code'          => 'area',
            'url'           => 'area',
            'main_menu'     => $master->id,
            'icon'          => 'bi bi-circle'
        ]);

        $materialManagement = Menu::create([
            'name'          => 'Material Management',
            'code'          => 'material-management',
            'url'           => 'material-management',
            'main_menu'     => null,
            'icon'          => 'bi bi-box-seam',
            'menu_hassub'   => 1,
            'sort'          => 3
        ]);

        // Create roles
        $roleSuperadmin = Role::create([
            'role_code' => 'superadmin',
            'role_name' => 'Super Admin'
        ]);

        $roleUser = Role::create([
            'role_code' => 'user',
            'role_name' => 'User'
        ]);
        

        // Create user
        $userSuperadmin = User::create([
            'name' => 'Super Admin',
            'username' => 'it',
            // 'email' => 'it@example.com',
            'password' => bcrypt('superadmin'),
            'role_id' => $roleSuperadmin->id
        ]);

        $usersUser = User::create([
            'name'      => 'User',
            'username'  => 'user',
            // 'email'  => 'user@example.com',
            'password'  => bcrypt('user'),
            'role_id'   => $roleUser->id
        ]);


        // Create role menu
        $roleMenuSuperadminDashboard = RoleMenu::create([
            'role_id' => $roleSuperadmin->id,
            'menu_id' => $dashboard->id
        ]);
        $roleMenuSuperadminMaster = RoleMenu::create([
            'role_id' => $roleSuperadmin->id,
            'menu_id' => $master->id
        ]);
        $roleMenuSuperadminSetting = RoleMenu::create([
            'role_id' => $roleSuperadmin->id,
            'menu_id' => $setting->id
        ]);
        $roleMenuSuperadminUser = RoleMenu::create([
            'role_id' => $roleSuperadmin->id,
            'menu_id' => $users->id
        ]);
        $roleMenuSuperadminRole = RoleMenu::create([
            'role_id' => $roleSuperadmin->id,
            'menu_id' => $roles->id
        ]);
        $roleMenuSuperadminConfig = RoleMenu::create([
            'role_id' => $roleSuperadmin->id,
            'menu_id' => $configurations->id
        ]);
        $roleMenuSuperAdminArea = RoleMenu::create([
            'role_id' => $roleSuperadmin->id,
            'menu_id' => $area->id,
        ]);

        $roleMenuSuperAdminMatMgmt = RoleMenu::create([
            'role_id' => $roleSuperadmin->id,
            'menu_id' => $materialManagement->id,
        ]);

        $roleMenuUser = RoleMenu::create([
            'role_id' => $roleUser->id,
            'menu_id' => $dashboard->id
        ]);


        // create configuration
        $configLogo = Configuration::create([
            'id'            => "6NwZwlU9ibONBYztkC9JBRXShW",
            'config_code'   => 'app.logo',
            'config_title'  => 'Logo',
            'config_value'  => '',
            'config_group'  => 'app'
        ]);

        $configDescription = Configuration::create([
            'id'            => "A7aYZCIOefQy3XSe7FTo6kuOm0",
            'config_code'   => 'app.description',
            'config_title'  => 'Description',
            'config_value'  => 'STARTER',
            'config_group'  => 'app'
        ]);

        $configName = Configuration::create([
            'id'            => "A7aYZCIOefQy3XSe7FTo6kuOm8",
            'config_code'   => 'app.name',
            'config_title'  => 'Application Name',
            'config_value'  => 'STARTER',
            'config_group'  => 'app'
        ]);
    }
}
