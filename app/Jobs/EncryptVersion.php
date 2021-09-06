<?php

namespace App\Jobs;

use App\Models\Version;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class EncryptVersion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Version $version,
        public string  $hash
    )
    {
    }

    public function handle()
    {
        // get the encrypter
        $jar = storage_path('app/encrypt.jar');

        // get the encryption info
        [$key, $iv] = config('version');

        $version = storage_path("app/versions/$this->hash.jar");
        $out = storage_path("app/versions/$this->hash.jar.enc");

        // encrypt the uploaded version
        exec("java -jar $jar $key $iv $version $out");

        // delete the uploaded version
        Storage::delete("versions/$this->hash.jar");

        // mark version as processed
        $this->version->update([
           'processed_at' => now(),
        ]);
    }
}
