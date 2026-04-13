<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $table = 'tb_content';
    protected $fillable = ['section', 'content_key', 'content_value', 'content_type'];
    public $timestamps = false;
}
