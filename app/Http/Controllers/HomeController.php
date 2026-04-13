<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Facility;

class HomeController extends Controller
{
    public function index()
    {
        $contentRows = Content::all();
        $content = [];
        foreach ($contentRows as $row) {
            $content[$row->content_key] = $row->content_value;
        }

        $default_content = [
            'hero_title' => 'Selamat Datang di Virtual Tour Prodi Sistem Informasi',
            'hero_subtitle' => 'Universitas Pamulang',
            'hero_description' => 'Jelajahi fasilitas modern dan lingkungan akademik yang mendukung proses pembelajaran di Program Studi Sistem Informasi melalui pengalaman virtual tour 360°',
            'facilities_title' => 'FASILITAS UNPAM',
            'facilities_description' => 'Temukan berbagai fasilitas modern yang mendukung proses belajar mengajar di Program Studi Sistem Informasi',
        ];

        foreach ($default_content as $key => $value) {
            if (!isset($content[$key]) || empty($content[$key])) {
                $content[$key] = $value;
            }
        }

        $facilities = Facility::orderBy('id')->get();

        return view('home', compact('content', 'facilities'));
    }
}
