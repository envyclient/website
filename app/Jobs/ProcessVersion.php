<?php

namespace App\Jobs;

use App\Models\Version;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ProcessVersion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private Version $version,
        private string $path,
    )
    {
    }

    public function handle()
    {
        $version = storage_path("app/$this->path/version.jar");
        $data_dir = storage_path("app/$this->path/data");

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
            Storage::disk('local')->allFiles("$this->path/data")
        )->map(function (string $path) {
            return substr($path, 51);
        })->toJson(JSON_UNESCAPED_SLASHES);

        // saving the manifest file
        Storage::disk('local')->put("$this->path/manifest.json", $manifest);

        // TODO: send discord webhook

        // deleting the version.jar
        Storage::disk('local')->delete("$this->path/version.jar");

        // mark the version as processed
        $this->version->update([
            'manifest' => "$this->path/manifest.json",
            'processed_at' => now(),
        ]);
    }
}

