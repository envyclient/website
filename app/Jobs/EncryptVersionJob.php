<?php

namespace App\Jobs;

use App\Models\Version;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpParser\Node\Scalar\String_;

class EncryptVersionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 15;

    public function __construct(
        private Version $version,
        private string  $hash
    )
    {
    }

    public function handle()
    {
        // get the encryption key
        $key = config('version.key');

        // read the version as a string
        $data = Storage::get("versions/$this->hash.jar");

        // encrypt the version
        $value = openssl_encrypt($data, "AES-256-CBC", $key, OPENSSL_RAW_DATA, hex2bin($this->version->iv));

        // store the encrypted version
        Storage::put("versions/$this->hash.jar.enc", bin2hex($value));

        // delete the uploaded version
        Storage::delete("versions/$this->hash.jar");

        // mark version as processed
        $this->version->update([
            'processed_at' => now(),
        ]);
    }
}
