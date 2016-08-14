<?php

namespace App\Http\Controllers\Auth;

use App\Settings;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Tumblr;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    public function login(Request $request, $provider = null)
    {
        if ($request->all()) {
            $userData = Socialite::with($provider)->user();
            switch ($provider) {
                case "twitter":
                    $userData->id = $userData->user['id_str'];
                    $userData->provider = 'twitter';
                    break;
                case "facebook":
                    $userData->provider = 'facebook';
                    break;
                case "tumblr":
                    $userData->provider = 'tumblr';
                    $userData->name = $userData->nickname;
                    $userData->id = "tumblr_" . $userData->nickname;
                    break;
            }

            $user = User::where('provider_id', $userData->id)->first();

            if (!$user) {
                $user = User::create([
                    'provider_id' => $userData->id,
                    'provider' => $userData->provider,
                    'name' => $userData->name,
                    'username' => $userData->nickname,
                    'email' => $userData->email,
                ]);
//                $user->save();
                Settings::create([
                    'user_id' => $user->id,
                    'share_to_our_tumblr' => true
                ]);
            }

            $user->save();
            Auth::login($user);
            return Redirect::route('editor');
        }
        return Socialite::with($provider)->redirect();
    }

    public function logout()
    {
        Auth::logout();
        return Redirect::route('index');
    }
}
