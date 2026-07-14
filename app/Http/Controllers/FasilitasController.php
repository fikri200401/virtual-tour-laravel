<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Services\WebsiteContentService;

class FasilitasController extends Controller
{
    public function index(WebsiteContentService $websiteContent)
    {
        $facilities = Facility::orderBy('id')->get();
        $content = $websiteContent->all();

        return view('fasilitas', compact('facilities', 'content'));
    }
}
