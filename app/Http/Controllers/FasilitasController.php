<?php

namespace App\Http\Controllers;

use App\Models\Facility;

class FasilitasController extends Controller
{
    public function index()
    {
        $facilities = Facility::orderBy('id')->get();

        return view('fasilitas', compact('facilities'));
    }
}
