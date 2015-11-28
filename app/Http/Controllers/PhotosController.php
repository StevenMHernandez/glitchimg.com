<?php namespace App\Http\Controllers;

use App\Images;
use App\Jobs\ShareToTumblr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tumblr;
use App\Helpers\UrlGenerator;

class PhotosController extends Controller
{

    public function index()
    {
        $gifs = DB::table('gifs')
            ->where('user_id', Auth::user()->id)
            ->select(['id', 'filename', 'user_id', 'created_at'])
            ->selectRaw('CONCAT("gif") as "type"');

        $photos = DB::table('images')
            ->where('user_id', Auth::user()->id)
            ->where('gif_id', null)
            ->select(['id', 'filename', 'user_id', 'created_at'])
            ->selectRaw('CONCAT("photo") as "type"')
            ->union($gifs)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('photos.index', compact('photos'));
    }

    public function show($filename)
    {
        $photo = Images::where('filename', $filename)->with('user')->first();
        $urls = UrlGenerator::buildAll($photo->filename);
        $photo->uri = UrlGenerator::build('preview_image', $photo->filename);
        return view('photos.show', compact('photo', 'urls'));
    }

    public function upload(Request $request)
    {
        $user = Auth::user();
        $user->with('settings');

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
            'user_id' => $user->id,
            'gif_id' => $request->gif_id
        ]);
        $image->save();

        if ($user->settings->share_to_our_tumblr && env('APP_ENV') != 'local' && empty($image->gif_id)) {
            $this->dispatch(new ShareToTumblr($user->name, $filename));
        }

        return [
            'urls' => UrlGenerator::buildAll($image->filename)
        ];
    }
}
