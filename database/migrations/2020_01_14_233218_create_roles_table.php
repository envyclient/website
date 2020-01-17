<?php

use App\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        // inserting default data
        DB::table('roles')->insert(
            ['name' => Role::DEFAULT[1]]
        );
        DB::table('roles')->insert(
            ['name' => Role::PREMIUM[1]]
        );
        DB::table('roles')->insert(
            ['name' => Role::ADMIN[1]]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
