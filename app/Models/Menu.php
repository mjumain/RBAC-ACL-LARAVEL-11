<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;


    public function submenus()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id', Route::class, 'route', 'route');
    }
    public function routes()
    {
        return $this->belongsTo(Route::class, 'route', 'route');
    }
    public function parent()
    {
        return $this->hasMany(Menu::class, 'id', 'parent_id');
    }
}
