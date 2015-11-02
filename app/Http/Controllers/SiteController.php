<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Tumblr;

class SiteController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function login()
    {
        return view('login');
    }
}