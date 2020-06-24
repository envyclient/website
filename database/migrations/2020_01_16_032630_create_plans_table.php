<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('description');
            $table->unsignedTinyInteger('price');
            $table->string('paypal_id')->unique();
            $table->unsignedTinyInteger('config_limit');
            $table->boolean('beta_access')->default(false);
            $table->boolean('capes_access')->default(false);
        });

        DB::table('plans')->insert([
            'name' => 'Standard',
            'description' => 'Get basic access to Envy Client for 30 days.',
            'price' => 7,
            'config_limit' => 5
        ]);

        DB::table('plans')->insert([
            'name' => 'Premium',
            'description' => 'Get premium access to Envy Client for 30 days.',
            'price' => 10,
            'config_limit' => 15,
            'beta_access' => true,
            'capes_access' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
