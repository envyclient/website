<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'api_token' => Str::random(60),
            'hwid' => Str::random(40),
            'cape' => null,
            'admin' => 0,
            'banned' => 0,
            'disabled' => 0,
            'access_free_plan' => 1,
            'remember_token' => Str::random(10),
        ];
    }
}
