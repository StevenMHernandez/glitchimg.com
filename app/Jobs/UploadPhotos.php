<?php

namespace App\Jobs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class UploadPhotos extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $filename;
    protected $large_base64;
    protected $preview_base64;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filename, $large_base64, $preview_base64)
    {
        $this->filename = $filename;
        $this->large_base64 = $large_base64;
        $this->preview_base64 = $preview_base64;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $disk = Storage::disk('s3');

        $largeData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $this->large_base64));
        file_put_contents(storage_path() . '/app/images/' . $this->filename . '.png', $largeData);
        $disk->put('full/' . $this->filename . '.png', file_get_contents(storage_path() . '/app/images/' . $this->filename . '.png', 'public'));
        unlink(storage_path() . '/app/images/' . $this->filename . '.png');

        $previewData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $this->preview_base64));
        file_put_contents(storage_path() . '/app/images/' . $this->filename . '.jpg', $previewData);
        $disk->put('preview/' . $this->filename . '.jpg', file_get_contents(storage_path() . '/app/images/' . $this->filename . '.jpg', 'public'));
        unlink(storage_path() . '/app/images/' . $this->filename . '.jpg');
    }
}
