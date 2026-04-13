<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorStat extends Model
{
    protected $table = 'tb_visitor_stats';
    protected $fillable = ['ip_address', 'user_agent', 'page_visited', 'visit_date', 'is_admin'];
    public $timestamps = false;
}
