<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VrScene extends Model
{
    protected $table = 'tb_vr_scenes';
    protected $fillable = ['name', 'description', 'scene_key', 'image_360', 'icon'];
    public $timestamps = false;

    public function hotspots()
    {
        return $this->hasMany(VrHotspot::class, 'scene_id');
    }
}
