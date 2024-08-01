<?php

namespace App\Http\Helpers;

use App\Models\Menu;
use App\Models\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MenuHelper
{
    public static function Menu()
    {
        $user = User::find(Auth::user()->id);
        $permissions = $user->getAllPermissions()->pluck('name')->toArray();

        $menus = Menu::with('routes')->with('submenus')->where('parent_id', 0)->get();

        foreach ($menus as $key => $value) {
            if (!empty($value->routes->permission_name)) {
                if (in_array($value->routes->permission_name, $permissions)) {
                    $data[] = $value;
                }
            }
        }


        return $menus;
    }
}
