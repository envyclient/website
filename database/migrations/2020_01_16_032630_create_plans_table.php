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
        });

        DB::table('plans')->insert([
            'name' => 'Monthly',
            'description' => 'Get access to the client for 30 days.',
            'price' => 7,
            'interval' => 30
        ]);

        DB::table('plans')->insert([
            'name' => 'Lifetime',
            'description' => 'Get access to the client for its lifetime.',
            'price' => 40,
            'interval' => 3650
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
