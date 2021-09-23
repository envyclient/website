<?php

namespace Database\Factories;

use App\Models\ReferralCode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ReferralCodeFactory extends Factory
{
    protected $model = ReferralCode::class;

    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomDigit(),
            'code' => Str::random(15),
        ];
    }
}
