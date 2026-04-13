<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\VrScene;
use App\Models\VrHotspot;

class VirtualTourController extends Controller
{
    public function index()
    {
        $contentRows = Content::all();
        $content = [];
        foreach ($contentRows as $row) {
            $content[$row->content_key] = $row->content_value;
        }

        $default_content = [
            'vr_title' => 'SELAMAT DATANG DI',
            'vr_subtitle' => 'VIRTUAL TOUR PRODI SISTEM INFORMASI',
            'vr_description' => 'Jelajahi fasilitas dan lingkungan kampus Universitas Pamulang secara virtual',
        ];

        foreach ($default_content as $key => $value) {
            if (!isset($content[$key]) || empty($content[$key])) {
                $content[$key] = $value;
            }
        }

        $scenes = VrScene::orderBy('id')->get();
        $hotspotsRaw = VrHotspot::orderBy('scene_id')->orderBy('id')->get();
        $hotspots = [];
        foreach ($hotspotsRaw as $h) {
            $hotspots[$h->scene_id][] = $h;
        }

        return view('virtual_tour', compact('content', 'scenes', 'hotspots'));
    }
}
