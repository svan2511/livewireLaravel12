<?php

namespace App\Jobs;

use App\Events\MemberImageUploaded;
use Cloudinary\Cloudinary;
use App\Models\Member;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class UploadMemberImage implements \Illuminate\Contracts\Queue\ShouldQueue
{
    use \Illuminate\Bus\Queueable, \Illuminate\Queue\SerializesModels , Dispatchable;

    public function __construct(
        public $memberId,
        public $localPath,
        public $oldPublicId = null
    ) {}

    public function handle()
    {
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ]
        ]);

        // Upload new image
        $result = $cloudinary->uploadApi()->upload(
            $this->localPath,
            ['folder' => 'members']
        );

        $member = Member::find($this->memberId);
        if (! $member) return;

        // Update DB
        $member->update([
            'mem_img' => $result['secure_url'],
            'img_public_id' => $result['public_id'],
        ]);

        // Delete old image
        if ($this->oldPublicId) {
            $cloudinary->uploadApi()->destroy($this->oldPublicId);
        }

    }
}
