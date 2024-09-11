<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;
    protected $connection = 'db_tagihan';
    protected $guarded = [];
    // protected $table = 'tabel_tagihan_dev';
    protected $table = 'tabel_tagihan';
    public $timestamps = false;
}
