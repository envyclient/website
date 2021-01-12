<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ClearInactiveAccounts extends Command
{
    protected $signature = 'minecraft:clear';
    protected $description = 'Reset all inactive minecraft accounts';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        User::where('current_account', '<>', null)
            ->whereDate('updated_at', '<=', today()->subDays(3))
            ->update([
                'current_account' => null,
            ]);
        return 0;
    }
}
