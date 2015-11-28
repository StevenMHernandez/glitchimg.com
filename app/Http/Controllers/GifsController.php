<?php namespace App\Http\Controllers;

use App\Gifs;
use App\Jobs\ShareToTumblr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tumblr;
use App\Helpers\UrlGenerator;

class GifsController extends Controller
{

    public function show($filename)
    {
        $gif = Gifs::where('filename', $filename)->with('user', 'images')->first();
        $gif->uri = UrlGenerator::build('gif', $gif->filename, 'gif');
        return view('gifs.show', compact('gif'));
    }

    public function getId()
    {
        $gif = Gifs::create([
            'user_id' => Auth::user()->id
        ]);
        return [
            'gif_id' => $gif->id
        ];
    }

    public function upload(Request $request)
    {
        $user = Auth::user();
        $user->with('settings');

        $disk = Storage::disk('s3');
        $filename = str_random(18);

        $request->file('data')->move(storage_path() . '/app/images/', $filename . '.gif');
        $disk->put('gif/' . $filename . '.gif', file_get_contents(storage_path() . '/app/images/' . $filename . '.gif', 'public'));
        unlink(storage_path() . '/app/images/' . $filename . '.gif');

        $gif = Gifs::find($request->gif_id);
        $gif->filename = $filename;
        $gif->save();

        if ($user->settings->share_to_our_tumblr && env('APP_ENV') != 'local') {
            $this->dispatch(new ShareToTumblr($user->name, $filename, 'gif'));
        }

        return [
            'urls' => [
                'glitchimg' => UrlGenerator::build('glitchimg', $gif->filename, 'gif'),
                'facebook' => UrlGenerator::build('facebook', $gif->filename, 'gif'),
                'twitter' => UrlGenerator::build('twitter', $gif->filename, 'gif'),
            ]
        ];
    }
}
