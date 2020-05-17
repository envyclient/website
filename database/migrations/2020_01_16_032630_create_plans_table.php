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
            $table->unsignedInteger('price');
            $table->unsignedInteger('interval');
            $table->unsignedTinyInteger('config_limit');
        });

        DB::table('plans')->insert([
            'name' => 'Monthly',
            'description' => 'Get access to the client for 30 days.',
            'price' => 7,
            'interval' => 30,
            'config_limit' => 5
        ]);

        DB::table('plans')->insert([
            'name' => '3 Months',
            'description' => 'Get access to the client for 90 days.',
            'price' => 18,
            'interval' => 90,
            'config_limit' => 10
        ]);

        DB::table('plans')->insert([
            'name' => 'Yearly',
            'description' => 'Get access to the client for 365 days.',
            'price' => 60,
            'interval' => 365,
            'config_limit' => 15
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
