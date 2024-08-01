<?php

namespace App\Http\Helpers;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MenuHelper
{
    public static function Menu()
    {
        $menus = Menu::with('submenus')->where('parent_id', 0)->get();
        foreach ($menus as $key => $value) {
            if (count($value->submenus) > 0 && $value->route == '#') {
                $data[] = $value;
            } elseif ($value->route != '#') {
                $data[] = $value;
            }
        }
        return $data;
    }
    public static function Permissions()
    {
        $user = User::find(Auth::user()->id);
        $permissions = $user->getAllPermissions()->pluck('name')->toArray();

        return $permissions;
    }
}
