<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('api_token', 60)->unique();
            $table->string('hwid', 40)->nullable()->unique();
            $table->string('cape');
            $table->uuid('current_account')->nullable();
            $table->boolean('admin')->default(false);
            $table->boolean('banned')->default(false);
            $table->string('stripe_id')->nullable();

            $table->unsignedBigInteger('referral_code_id')->nullable();
            $table->timestamp('referral_code_used_at')->nullable();

            $table->string('discord_id', 18)->nullable()->unique();
            $table->string('discord_name')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
