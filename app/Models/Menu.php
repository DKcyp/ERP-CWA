<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $guarded = ['id'];
    protected $fillable = [
        'code',
        'name',
        'url',
        'icon',
        'main_menu',
        'has_submenu',
        'sort',
        'active',
    ];

    public function subMenus()
    {
        return $this->hasMany(Menu::class, 'main_menu')
        ->where('active', 1)->orderBy('sort','desc');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_menus', 'menu_id', 'role_id');
    }

    public function menu_select($role_id)
    {
        return $this->select('menus.*')
        ->selectSub(function ($query) use ($role_id) {
            $query->select('role_menus.id')
            ->from('role_menus')
            ->whereColumn('role_menus.menu_id', 'menus.id')
            ->where('role_menus.deleted_at', null)
            ->where('role_menus.role_id', $role_id)
            ->distinct();
        }, 'menu_selected')
        ->from('menus')
        ->get();
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'main_menu', 'id')
        ->where('active', 1)
        ->orderBy('sort')
        ->with('children');
    }

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'main_menu', 'id');
    }

    public static function selectWithSelectedByRole($roleId)
    {
        return self::query()
        ->leftJoin('role_menus as rm', function ($j) use ($roleId) {
            $j->on('rm.menu_id', '=', 'menus.id')->where('rm.role_id', '=', $roleId);
        })
        ->select(
            'menus.id',
            'menus.name',
            'menus.main_menu',
            'menus.menu_hassub',
            DB::raw('COALESCE(menus.sort_order, 9999) AS sort_order'),
            DB::raw('CASE WHEN rm.menu_id IS NULL THEN 0 ELSE 1 END AS menu_selected')
        )
        ->orderByRaw('CASE WHEN menus.main_menu IS NULL THEN 0 ELSE 1 END')
        ->orderBy('menus.main_menu')
        ->orderBy(DB::raw('COALESCE(menus.sort_order, 9999)'))
        ->orderBy('menus.name')
        ->get();
    }
}