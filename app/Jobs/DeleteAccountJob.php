<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class DeleteAccountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private User $user,
    )
    {
    }

    public function handle()
    {
        // delete license_requests
        $this->user->licenseRequests()->delete();

        // delete password_resets
        DB::table('password_resets')->where('email', $this->user->email)->delete();

        // delete stripe_source & stripe_source_events
        $this->user->stripeSources->each->delete();

        // delete the user
        $this->user->delete();
    }
}
