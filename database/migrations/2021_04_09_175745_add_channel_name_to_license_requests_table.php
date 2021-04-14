<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChannelNameToLicenseRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('license_requests', function (Blueprint $table) {
            $table->string('channel_name');
            $table->string('channel_image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('license_requests', function (Blueprint $table) {
            $table->removeColumn('channel_name');
            $table->removeColumn('channel_image');
        });
    }
}
