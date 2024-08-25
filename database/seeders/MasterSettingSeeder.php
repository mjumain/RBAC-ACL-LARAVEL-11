<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Route;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MasterSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Administrator', 'email' => 'admin@admin.com', 'password' => bcrypt('password')],
        ];
        User::insert($users);

        $permissions = [
            ['name' => 'permission-store', 'guard_name' => 'web'],
            ['name' => 'permission-index', 'guard_name' => 'web'],
            ['name' => 'permission-create', 'guard_name' => 'web'],
            ['name' => 'permission-update', 'guard_name' => 'web'],
            ['name' => 'permission-edit', 'guard_name' => 'web'],
            ['name' => 'permission-delete', 'guard_name' => 'web'],
            ['name' => 'role-store', 'guard_name' => 'web'],
            ['name' => 'role-index', 'guard_name' => 'web'],
            ['name' => 'role-create', 'guard_name' => 'web'],
            ['name' => 'role-uodate', 'guard_name' => 'web'],
            ['name' => 'role-edit', 'guard_name' => 'web'],
            ['name' => 'role-delete', 'guard_name' => 'web'],
            ['name' => 'menu-store', 'guard_name' => 'web'],
            ['name' => 'menu-index', 'guard_name' => 'web'],
            ['name' => 'menu-create', 'guard_name' => 'web'],
            ['name' => 'menu-update', 'guard_name' => 'web'],
            ['name' => 'menu-edit', 'guard_name' => 'web'],
            ['name' => 'menu-delete', 'guard_name' => 'web'],
            ['name' => 'user-store', 'guard_name' => 'web'],
            ['name' => 'user-index', 'guard_name' => 'web'],
            ['name' => 'user-create', 'guard_name' => 'web'],
            ['name' => 'user-update', 'guard_name' => 'web'],
            ['name' => 'user-edit', 'guard_name' => 'web'],
            ['name' => 'user-delete', 'guard_name' => 'web'],
            ['name' => 'route-store', 'guard_name' => 'web'],
            ['name' => 'route-index', 'guard_name' => 'web'],
            ['name' => 'route-create', 'guard_name' => 'web'],
            ['name' => 'route-update', 'guard_name' => 'web'],
            ['name' => 'route-edit', 'guard_name' => 'web'],
            ['name' => 'route-delete', 'guard_name' => 'web'],
        ];
        Permission::insert($permissions);

        $roles = [
            ['name' => 'superadmin', 'guard_name' => 'web'],
        ];
        $permission = Permission::all();
        Role::insert($roles);

        $role = Role::findByName('superadmin');
        $role->syncPermissions($permission);

        $user = User::find(1);
        $user->assignRole('superadmin');

        $menus = [
            ['name' => 'Settings', 'icon' => '<i class="nav-icon fas fa-cogs"></i>', 'parent_id' => '0', 'posision' => '0', 'route' => '#'],
            ['name' => 'Permissions', 'icon' => 'default', 'parent_id' => '1', 'posision' => '0', 'route' => 'permissions.index'],
            ['name' => 'Roles', 'icon' => 'default', 'parent_id' => '1', 'posision' => '0', 'route' => 'roles.index'],
            ['name' => 'Menus', 'icon' => 'default', 'parent_id' => '1', 'posision' => '0', 'route' => 'menus.index'],
            ['name' => 'Users', 'icon' => 'default', 'parent_id' => '1', 'posision' => '0', 'route' => 'users.index'],
            ['name' => 'Routes', 'icon' => 'default', 'parent_id' => '1', 'posision' => '0', 'route' => 'routes.index'],
        ];
        Menu::insert($menus);

        $routes = [
            ['permission_name' => 'permission-store', 'route' => 'permissions.store'],
            ['permission_name' => 'permission-index', 'route' => 'permissions.index'],
            ['permission_name' => 'permission-create', 'route' => 'permissions.create'],
            ['permission_name' => 'permission-update', 'route' => 'permissions.update'],
            ['permission_name' => 'permission-edit', 'route' => 'permissions.edit'],
            ['permission_name' => 'permission-delete', 'route' => 'permissions.delete'],
            ['permission_name' => 'role-store', 'route' => 'roles.store'],
            ['permission_name' => 'role-index', 'route' => 'roles.index'],
            ['permission_name' => 'role-create', 'route' => 'roles.create'],
            ['permission_name' => 'role-update', 'route' => 'roles.update'],
            ['permission_name' => 'role-edit', 'route' => 'roles.edit'],
            ['permission_name' => 'role-delete', 'route' => 'roles.delete'],
            ['permission_name' => 'menu-store', 'route' => 'menus.store'],
            ['permission_name' => 'menu-index', 'route' => 'menus.index'],
            ['permission_name' => 'menu-create', 'route' => 'menus.create'],
            ['permission_name' => 'menu-update', 'route' => 'menus.update'],
            ['permission_name' => 'menu-edit', 'route' => 'menus.edit'],
            ['permission_name' => 'menu-delete', 'route' => 'menus.delete'],
            ['permission_name' => 'user-store', 'route' => 'users.store'],
            ['permission_name' => 'user-index', 'route' => 'users.index'],
            ['permission_name' => 'user-create', 'route' => 'users.create'],
            ['permission_name' => 'user-update', 'route' => 'users.update'],
            ['permission_name' => 'user-edit', 'route' => 'users.edit'],
            ['permission_name' => 'user-delete', 'route' => 'users.delete'],
            ['permission_name' => 'route-store', 'route' => 'routes.store'],
            ['permission_name' => 'route-index', 'route' => 'routes.index'],
            ['permission_name' => 'route-create', 'route' => 'routes.create'],
            ['permission_name' => 'route-update', 'route' => 'routes.update'],
            ['permission_name' => 'route-edit', 'route' => 'routes.edit'],
            ['permission_name' => 'route-delete', 'route' => 'routes.delete'],
        ];
        Route::insert($routes);
    }
}
