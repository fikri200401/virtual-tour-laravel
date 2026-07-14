<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Services\WebsiteContentService;

class HomeController extends Controller
{
    public function index(WebsiteContentService $websiteContent)
    {
        $content = $websiteContent->all();
        $facilities = Facility::orderBy('id')->get();

        return view('home', compact('content', 'facilities'));
    }
}
