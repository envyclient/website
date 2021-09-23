<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stripe_source_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stripe_source_id')->constrained('stripe_sources');
            $table->string('type', 15);
            $table->string('message');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stripe_source_events');
    }
};
