<?php

namespace App\Http\Helpers;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MenuHelper
{
    public static function Menu()
    {
        $menus = Menu::with('submenus')->where('parent_id', 0)->orderBy('name', 'ASC')->get();
        if (count($menus) > 0) {
            foreach ($menus as $key => $value) {
                $data['route'] = [];
                if (count($value->submenus) > 0 && $value->route == '#') {
                    $data['menu'] = $value;
                    foreach ($value->submenus as $item) {
                        if (in_array($item->routes->permission_name ?? '', self::Permissions())) {
                            $data['route'][] = $item->route;
                        }
                    }
                    if (empty($data['route'])) {
                        $data['menu'] = [];
                    }
                } elseif ($value->route != '#') {
                    $data['menu'] = $value;
                    $data['route'][] = $value->route;
                }
                $cetak[] = $data;
            }
        } else {
            $data['menu'] = [];
            $cetak[] = $data;
        }

        return $cetak;
    }
    public static function Permissions()
    {
        $user = User::find(Auth::user()->id);
        $permissions = $user->getAllPermissions()->pluck('name')->toArray();

        return $permissions;
    }
}
