<?php

namespace App\Http\Controllers;

use App\Images;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tumblr;
use App\Helpers\UrlGenerator;

class PhotosController extends Controller
{

    public function index()
    {
        $photos = Images::where('user_id', Auth::user()->id)
            ->orderBy('id', 'desc')
            ->get();
        return view('photos.index', compact('photos'));
    }

    public function show($filename)
    {
        $photo = Images::where('filename', $filename)->with('user')->first();
        $urls = UrlGenerator::buildAll($photo->id);
        $photo->uri = config('filesystems.disks.s3.url') . 'preview/' . $filename . '.jpg';
        return view('photos.show', compact('photo', 'urls'));
    }

    public function upload(Request $request)
    {
        $disk = Storage::disk('s3');
        $filename = str_random(18);

        $largeData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->large));
        file_put_contents(storage_path() . '/app/images/' . $filename . '.png', $largeData);
        $disk->put('full/' . $filename . '.png', file_get_contents(storage_path() . '/app/images/' . $filename . '.png', 'public'));
        unlink(storage_path() . '/app/images/' . $filename . '.png');

        $previewData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->preview));
        file_put_contents(storage_path() . '/app/images/' . $filename . '.jpg', $previewData);
        $disk->put('preview/' . $filename . '.jpg', file_get_contents(storage_path() . '/app/images/' . $filename . '.jpg', 'public'));
        unlink(storage_path() . '/app/images/' . $filename . '.jpg');

        $image = Images::create([
            'filename' => $filename,
            'orientation' => $request->orientation,
            'ratio' => $request->ratio,
            'user_id' => Auth::user()->id
        ]);
        $image->save();

        $urls = [
            'shareable' => config('filesystems.disks.s3.url') . 'full/' . $filename . '.png',
            'direct' => config('filesystems.disks.s3.direct_url') . 'full/' . $filename . '.png',
            'page' => route('photos.show', $filename)
        ];

        $user = Auth::user();
        $user->with('settings');
        if ($user->settings->share_to_our_tumblr && env('APP_ENV') != 'local') {
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
            'urls' => UrlGenerator::buildAll($image->id)
        ];
    }
}
