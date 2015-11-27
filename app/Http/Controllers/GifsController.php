<?php namespace App\Http\Controllers;

use App\Gifs;
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
        $gif->uri = UrlGenerator::build('gif', $gif->id, 'gif');
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
        $disk = Storage::disk('s3');
        $filename = str_random(18);

        $request->file('data')->move(storage_path() . '/app/images/', $filename . '.gif');
        $disk->put('gif/' . $filename . '.gif', file_get_contents(storage_path() . '/app/images/' . $filename . '.gif', 'public'));
        unlink(storage_path() . '/app/images/' . $filename . '.gif');

        $gif = Gifs::find($request->gif_id);
        $gif->filename = $filename;
        $gif->save();

        $urls = [
            'shareable' => config('filesystems.disks.s3.url') . 'gif/' . $filename . '.gif',
            'direct' => config('filesystems.disks.s3.direct_url') . 'gif/' . $filename . '.gif',
            'page' => route('gifs.show', $filename)
        ];

        $user = Auth::user();
        $user->with('settings');
        if ($user->settings->share_to_our_tumblr && env('APP_ENV') != 'local' && empty($request->gif_id)) {
            $client = new Tumblr\API\Client(env('TUMBLR_KEY'), env('TUMBLR_SECRET'));
            $client->setToken(env('TUMBLR_TOKEN'), env('TUMBLR_TOKEN_SECRET'));
            $client->createPost('created-at-glitchimg', [
                'type' => 'photo',
                'state' => 'queue',
                'tags' => 'glitch,glitchart,glitch art,glitchimg.com',
                'caption' => 'Glitch art created by ' . $user->name . ' at <a href="http://glitchimg.com">glitchimg.com</a>',
                'link' => $urls['page'],
                'source' => $urls['shareable'],
            ]);
        }

        return [
            'urls' => [
                'glitchimg' => UrlGenerator::build('glitchimg', $request->gif_id, 'gif'),
                'facebook' => UrlGenerator::build('facebook', $request->gif_id, 'gif'),
                'twitter' => UrlGenerator::build('twitter', $request->gif_id, 'gif'),
            ]
        ];
    }
}
