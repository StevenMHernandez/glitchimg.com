<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Tumblr;
use Laravel\Socialite\Facades\Socialite;

class TumblrAuthController extends Controller
{

    public function redirectToProvider()
    {
        return Socialite::with('tumblr')->redirect();
    }

    public function handleProviderCallback()
    {
        $user = Socialite::with('tumblr')->user();
        dd($user);

        // $user->token;
    }
}