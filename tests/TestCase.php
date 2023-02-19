<?php

namespace Tests;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    private static function createUser(array $data, bool $subscribed): User
    {
        $user = User::factory()->create($data);

        if ($subscribed) {
            Subscription::create([
                'user_id' => $user->id,
                'plan_id' => 1,
                'status' => SubscriptionStatus::ACTIVE->value,
                'end_date' => now()->addMonth(),
            ]);
        }

        return $user;
    }

    protected static function user(array $data = [], bool $subscribed = false): User
    {
        return self::createUser(
            array_merge($data, ['admin' => false]),
            $subscribed
        );
    }

    protected static function admin(array $data = [], bool $subscribed = false): User
    {
        return self::createUser(
            array_merge($data, ['admin' => true]),
            $subscribed
        );
    }
}
