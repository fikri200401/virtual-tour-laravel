<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VrHotspot extends Model
{
    protected $table = 'tb_vr_hotspots';
    protected $fillable = ['scene_id', 'name', 'target_scene', 'position_x', 'position_y', 'position_z'];
    public $timestamps = false;

    public function scene()
    {
        return $this->belongsTo(VrScene::class, 'scene_id');
    }
}
