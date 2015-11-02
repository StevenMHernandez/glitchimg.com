<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Tumblr;

class EditorController extends Controller
{

    public function index() {
        return view('editor');
    }
}