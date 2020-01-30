<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $userClass = app('App\User');
        Schema::create('wallets', function (Blueprint $table) use ($userClass) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->bigInteger('balance')->default(0);
            $table->timestamps();
            $table->foreign($userClass->getForeignKey())
                ->references($userClass->getKeyName())
                ->on($userClass->getTable())
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallets');
    }
}
