<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $table = 'tb_facilities';
    protected $fillable = ['name', 'description', 'image', 'is_active'];
    const UPDATED_AT = 'updated_at';
    const CREATED_AT = 'created_at';
}
