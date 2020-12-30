<?php

namespace Database\Seeders;

use App\Models\Config;
use App\Models\User;
use App\Models\Version;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->times(15)
            ->create();
        Config::factory()
            ->times(50)
            ->create();
        Version::factory()
            ->times(10)
            ->create();
    }
}
