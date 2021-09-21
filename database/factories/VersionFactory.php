<?php

namespace Database\Factories;

use App\Models\Version;
use Illuminate\Database\Eloquent\Factories\Factory;

class VersionFactory extends Factory
{
    protected $model = Version::class;

    public function definition(): array
    {
        return [
            'name' => 'Envy ' . $this->faker->randomFloat(1, 1, 9),
            'beta' => $this->faker->boolean(),
            'changelog' => 'test\ntest\ntest\n',
            'main_class' => 'test',
            'processed_at' => now(),
        ];
    }
}
