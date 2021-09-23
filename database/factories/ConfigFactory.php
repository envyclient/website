<?php

namespace Database\Factories;

use App\Models\Config;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConfigFactory extends Factory
{
    protected $model = Config::class;

    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomNumber(),
            'version_id' => $this->faker->randomNumber(),
            'name' => $this->faker->firstName,
            'data' => json_encode([
                'name' => 'testing'
            ]),
            'public' => $this->faker->boolean(),
            'official' => $this->faker->boolean(),
        ];
    }
}
