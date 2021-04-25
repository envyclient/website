<?php

namespace App\Jobs;

use App\Models\Version;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
        $version = storage_path('app/' . $this->path . '/version.jar');

        // TODO: handle version
        $output = null;
        $return = null;
        exec(
            "java -jar $jar $version data 123",
            $output,
            $return
        );

        info($return);

        // TODO: send discord webhook

        // mark the version as processed
        $this->version->update([
            'processed_at' => now()
        ]);
    }
}
