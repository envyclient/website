<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description');
            $table->unsignedTinyInteger('price');
            $table->unsignedSmallInteger('cad_price');
            $table->string('paypal_id')->unique()->nullable();
            $table->string('stripe_id')->unique()->nullable();
            $table->unsignedTinyInteger('config_limit');
            $table->boolean('beta_access');
            $table->boolean('capes_access');
        });

        DB::table('plans')->insert([
            [
                'name' => 'Free',
                'description' => 'Get free access to Envy Client for 30 days.',
                'price' => 0,
                'cad_price' => 0,
                'config_limit' => 15,
                'beta_access' => true,
                'capes_access' => true,
            ],
            [
                'name' => 'Standard',
                'description' => 'Get standard access to Envy Client for 30 days.',
                'price' => 7,
                'cad_price' => 900, // $9
                'config_limit' => 5,
                'beta_access' => false,
                'capes_access' => false,
            ],
            [
                'name' => 'Premium',
                'description' => 'Get premium access to Envy Client for 30 days.',
                'price' => 10,
                'cad_price' => 1300, // $13
                'config_limit' => 15,
                'beta_access' => true,
                'capes_access' => true,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
