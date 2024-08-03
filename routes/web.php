<?php

use App\Http\Controllers\ErrorController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth');
Route::post('/change-password', [ProfileController::class, 'change_password'])->middleware('auth');
Route::group(['middleware' => ['auth', 'cekakses']], function () {
    Route::resource('roles', RoleController::class)->except('show');
    Route::resource('permissions', PermissionController::class)->except('show');
    Route::resource('routes', RouteController::class)->except('show');
    Route::resource('users', UserController::class)->except('show');
    Route::resource('menus', MenuController::class)->except('show');
    Route::get('401', [ErrorController::class, 'error401']);
});
