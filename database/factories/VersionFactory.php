<?php

namespace Database\Factories;

use App\Models\Version;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VersionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Version::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->firstName,
            'beta' => $this->faker->boolean(),
            'version' => 'versions/' . Str::random(20) . '/version.exe',
            'assets' => 'versions/' . Str::random(20) . '/assets.jar',
            'changelog' => 'test\ntest\ntest\n',
        ];
    }
}
