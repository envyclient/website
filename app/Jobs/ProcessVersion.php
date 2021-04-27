<?php

namespace App\Jobs;

use App\Models\Version;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ProcessVersion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private Version $version,
        private string $folder,
    )
    {
    }

    public function handle()
    {
        $version = storage_path("app/versions/$this->folder/version.jar");
        $data_dir = storage_path("app/versions/$this->folder/data");

        // extract the version file
        $zip = new ZipArchive();
        if ($zip->open($version) === TRUE) {
            $zip->extractTo($data_dir);
            $zip->close();
        } else {
            $this->fail();
        }

        // generating the manifest data
        $manifest = collect(
            Storage::disk('local')->allFiles("versions/$this->folder/data")
        )->map(function (string $line) {
            return substr($line, 51);
        })->map(function (string $line) {
            return Crypt::encryptString($line);
        })->toJson(JSON_UNESCAPED_SLASHES);

        // saving the manifest file
        Storage::disk('local')->put("versions/$this->folder/manifest.json", $manifest);

        // TODO: send discord webhook

        // deleting the version.jar
        Storage::disk('local')->delete("versions/$this->folder/version.jar");

        // mark the version as processed
        $this->version->update([
            'processed_at' => now(),
        ]);
    }
}

