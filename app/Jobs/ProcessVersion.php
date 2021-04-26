<?php

namespace App\Jobs;

use App\Models\Version;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

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
        $jar = storage_path('app/extract.jar');
        $version = storage_path("app/$this->path/version.jar");
        $path = storage_path("app/$this->path");

        // TODO: handle version
        exec("java -jar $jar $path $version data -1");

        // TODO: send discord webhook

        // deleting the version.jar
        Storage::disk('local')->delete("$this->path/version.jar");

        // mark the version as processed
        $this->version->update([
            'manifest' => "$this->path/data.json",
            'processed_at' => now()
        ]);
    }
}
