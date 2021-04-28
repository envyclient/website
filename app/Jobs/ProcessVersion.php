<?php

namespace App\Jobs;

use App\Models\Version;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use SoareCostin\FileVault\Facades\FileVault;
use ZipArchive;

class ProcessVersion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private Version $version,
        private string $folder,
        private string $mainClass,
    )
    {
    }

    public function handle()
    {
        // extract the version file
        $zip = new ZipArchive();
        if ($zip->open(storage_path("app/versions/$this->folder/version.jar")) === true) {
            $zip->extractTo(storage_path("app/versions/$this->folder/data"));
            $zip->close();
        } else {
            $this->fail();
        }

        // encrypt the files
        foreach (Storage::disk('local')->allFiles("versions/$this->folder/data") as $file) {
            FileVault::encrypt($file);
        }

        // generating the manifest data
        $files = collect(
            Storage::disk('local')->allFiles("versions/$this->folder/data")
        )->map(function (string $line) {
            return substr($line, 47);
        })->map(function (string $line) {
            return base64_encode($line);
        })->toArray();

        // saving the manifest file
        Storage::disk('local')->put("versions/$this->folder/manifest.json", json_encode([
            'main' => $this->mainClass,
            'files' => $files,
        ], JSON_UNESCAPED_SLASHES));

        // TODO: send discord webhook

        // deleting the version.jar
        Storage::disk('local')->delete("versions/$this->folder/version.jar");

        sleep(2);

        // mark the version as processed
        $this->version->update([
            'processed_at' => now(),
        ]);
    }
}

