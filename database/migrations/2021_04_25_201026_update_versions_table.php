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
            // remove unused columns
            $table->removeColumn('version');
            $table->removeColumn('assets');

            // add new columns
            $table->string('manifest')->nullable();
            $table->timestamp('processed_at')->nullable();
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
            // add back the removed columns
            $table->string('version')->unique();
            $table->string('assets')->unique();

            // remove the new columns
            $table->removeColumn('manifest');
            $table->removeColumn('processed_at');
        });
    }
}
