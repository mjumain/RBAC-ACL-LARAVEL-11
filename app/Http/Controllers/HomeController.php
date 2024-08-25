<?php

namespace App\Http\Controllers;

use App\Http\Helpers\MenuHelper;
use App\Models\Menu;
use App\Models\Route;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route as FacadesRoute;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $data['roles'] = Role::all();
        $data['permissions'] = Permission::all();
        $data['users'] = User::all();
        $data['menus'] = Menu::all();
        return view('home')->with($data);
    }
}
