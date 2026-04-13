<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'tb_admin';
    protected $fillable = ['username', 'password'];
    public $timestamps = false;

    protected $hidden = ['password'];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
