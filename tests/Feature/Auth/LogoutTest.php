<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_log_out()
    {
        $user = self::user();

        $this->be($user);

        $this->post(route('logout'))
            ->assertRedirect('/');

        $this->assertFalse(auth()->check());
    }

    /** @test */
    public function an_unauthenticated_user_can_not_log_out()
    {
        $this->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertFalse(auth()->check());
    }
}
