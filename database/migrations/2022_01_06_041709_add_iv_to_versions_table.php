<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('versions', function (Blueprint $table) {
            $table->char('iv', 32)->after('main_class');
        });
    }

    public function down()
    {
        Schema::table('versions', function (Blueprint $table) {
            $table->dropColumn('iv');
        });
    }
};
