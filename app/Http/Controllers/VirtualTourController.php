<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\VrScene;
use App\Models\VrHotspot;
use Illuminate\Support\Facades\File;

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

        // Scan for 3DVista virtual tours in public/virtual-tours/
        $virtualTours = [];
        $tourPath = public_path('virtual-tours');
        if (File::isDirectory($tourPath)) {
            $directories = File::directories($tourPath);
            foreach ($directories as $dir) {
                $dirName = basename($dir);
                $indexFile = $dir . DIRECTORY_SEPARATOR . 'index.htm';
                if (!File::exists($indexFile)) {
                    $indexFile = $dir . DIRECTORY_SEPARATOR . 'index.html';
                }
                if (File::exists($indexFile)) {
                    $virtualTours[] = [
                        'slug' => $dirName,
                        'name' => ucfirst(str_replace(['-', '_'], ' ', $dirName)),
                        'url' => asset('virtual-tours/' . $dirName . '/' . basename($indexFile)),
                        'icon' => $this->getTourIcon($dirName),
                    ];
                }
            }
        }

        return view('virtual_tour', compact('content', 'scenes', 'hotspots', 'virtualTours'));
    }

    /**
     * Get an appropriate Font Awesome icon based on tour name.
     */
    private function getTourIcon(string $name): string
    {
        $icons = [
            'musolah' => 'fas fa-mosque',
            'mushola' => 'fas fa-mosque',
            'masjid' => 'fas fa-mosque',
            'parkiran' => 'fas fa-car',
            'parking' => 'fas fa-car',
            'kelas' => 'fas fa-chalkboard-teacher',
            'perpustakaan' => 'fas fa-book',
            'auditorium' => 'fas fa-theater-masks',
            'laboratorium' => 'fas fa-flask',
            'kantin' => 'fas fa-utensils',
        ];

        $nameLower = strtolower($name);
        foreach ($icons as $keyword => $icon) {
            if (str_contains($nameLower, $keyword)) {
                return $icon;
            }
        }

        return 'fas fa-street-view';
    }
}

