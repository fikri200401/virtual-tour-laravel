<?php

namespace App\Http\Controllers;

use App\Services\WebsiteContentService;

class TentangController extends Controller
{
    public function index(WebsiteContentService $websiteContent)
    {
        $content = $websiteContent->all();

        return view('tentang', compact('content'));
    }
}
