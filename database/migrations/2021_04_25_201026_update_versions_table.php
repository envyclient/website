<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('versions', function (Blueprint $table) {
            // drop the no longer used columns
            $table->dropColumn(['version', 'assets']);

            // add a new main_class column
            $table->string('main_class')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('versions', function (Blueprint $table) {
            // add the dropped columns back
            $table->string('version')->unique();
            $table->string('assets')->unique();

            // drop the new main_class column
            $table->dropColumn('main_class');
        });
    }
}
