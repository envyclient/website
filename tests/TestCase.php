<?php

namespace Tests;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    private static function createUser(array $data): User
    {
        return User::factory()->create($data);
    }

    protected static function user(): User
    {
        return self::createUser(['admin' => false]);
    }

    protected static function admin(): User
    {
        return self::createUser(['admin' => true]);
    }

    protected static function subscribedUser(): User
    {
        $user = self::user();
        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => 1,
            'status' => \App\Enums\Subscription::ACTIVE->value,
            'end_date' => now()->addMonth(),
        ]);
        return $user;
    }
}
