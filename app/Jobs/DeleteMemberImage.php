<?php

namespace App\Jobs;

use Cloudinary\Cloudinary;
use Illuminate\Foundation\Bus\Dispatchable;

class DeleteMemberImage implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use \Illuminate\Bus\Queueable, \Illuminate\Queue\SerializesModels , Dispatchable;

    public function __construct(public $publicId) {}

    public function handle()
    {
        if (!$this->publicId) return;

        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ]
        ]);

        $cloudinary->uploadApi()->destroy($this->publicId);
    }
}
