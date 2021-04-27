<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    private function createUser(array $data): User
    {
        return User::factory()->create($data);
    }

    protected function user(array $data = ['admin' => false]): User
    {
        return $this->createUser($data);
    }

    protected function admin(array $data = ['admin' => true]): User
    {
        return $this->createUser($data);
    }
}
