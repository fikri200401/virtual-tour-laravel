<?php

namespace App\Http\Controllers;

use App\Services\WebsiteContentService;
use App\Services\VirtualTourUploadService;

class VirtualTourController extends Controller
{
    public function index(
        WebsiteContentService $websiteContent,
        VirtualTourUploadService $tourUploader
    )
    {
        $content = $websiteContent->all();
        $virtualTour = $tourUploader->installedTour();

        return view('virtual_tour', compact('content', 'virtualTour'));
    }
}
