<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Facility extends Model
{
    protected $table = 'tb_facilities';

    protected $fillable = ['name', 'description', 'image', 'virtual_tour_slug', 'is_active'];

    const UPDATED_AT = 'updated_at';

    const CREATED_AT = 'created_at';

    public function getVirtualTourUrlAttribute(): ?string
    {
        $slug = $this->attributes['virtual_tour_slug'] ?? null;

        if (! is_string($slug) || $slug === '' || $slug !== basename($slug) || str_contains($slug, '..')) {
            return null;
        }

        foreach (['index.htm', 'index.html'] as $indexFile) {
            if (File::isFile(public_path('facility-tours/'.$slug.'/'.$indexFile))) {
                return asset('facility-tours/'.$slug.'/'.$indexFile);
            }
        }

        return null;
    }
}
