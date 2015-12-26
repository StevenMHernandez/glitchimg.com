<?php

namespace App\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Tumblr;

class ShareToTumblr extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $username;
    protected $filename;
    protected $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($username, $filename, $type = null)
    {
        $this->username = $username;
        $this->filename = $filename;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->type == 'gif') {
            $urls = [
                'shareable' => config('filesystems.disks.s3.url') . 'gif/' . $this->filename . '.gif',
                'direct' => config('filesystems.disks.s3.direct_url') . 'gif/' . $this->filename . '.gif',
                'page' => route('gifs.show', $this->filename)
            ];
            $tags = 'glitch,glitchart,glitch art,glitchimg.com';
            $art_type = 'Glitch art';
        } else {
            $urls = [
                'shareable' => config('filesystems.disks.s3.url') . 'full/' . $this->filename . '.png',
                'direct' => config('filesystems.disks.s3.direct_url') . 'full/' . $this->filename . '.png',
                'page' => route('photos.show', $this->filename)
            ];
            $tags = 'glitch,glitch art,glitch gif,gif,glitchimg.com';
            $art_type = 'Glitch gif';
        }

        $client = new Tumblr\API\Client(env('TUMBLR_KEY'), env('TUMBLR_SECRET'));
        $client->setToken(env('TUMBLR_TOKEN'), env('TUMBLR_TOKEN_SECRET'));
        $client->createPost('created-at-glitchimg', [
            'type' => 'photo',
            'state' => 'queue',
            'tags' => $tags,
            'caption' => $art_type . ' created by ' . $this->username . ' at <a href="http://glitchimg.com">glitchimg.com</a>',
            'link' => $urls['page'],
            'source' => $urls['shareable'],
        ]);
    }
}
