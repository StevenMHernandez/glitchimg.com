<?php

namespace App\Http\Controllers;

use App\Helpers\UrlGenerator;
use App\Images;
use Illuminate\Support\Facades\Cache;

class SiteController extends Controller
{
    public function index()
    {
        $randomImage = Cache::remember('random_image', 5, function()
        {
            return UrlGenerator::build('full_image', Images::orderByRaw("RAND()")->first()->filename);
        });
        return view('index', compact('randomImage'));
    }

    public function login()
    {
        return view('login');
    }
}