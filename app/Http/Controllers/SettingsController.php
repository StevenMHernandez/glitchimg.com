<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Tumblr;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->with('settings');
        return view('settings.index', compact('user'));
    }

    public function update(Request $request)
    {
        $settings = Settings::where('user_id', Auth::user()->id)->first();
        $settings->share_to_our_tumblr = $request->input('share_to_our_tumblr') ? true : false;
        $settings->save();
        Session::flash('message', 'Settings Saved.');
        return Redirect::route('settings');
    }
}