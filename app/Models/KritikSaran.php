<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KritikSaran extends Model
{
    protected $table = 'tb_kritik_saran';
    protected $fillable = ['nama', 'kontak', 'pesan'];
    public $timestamps = false;
}
