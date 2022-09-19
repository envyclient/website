<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_authenticated_user_logout(): void
    {
        $user = User::factory()->create();
        $this->be($user)
            ->post('/logout')
            ->assertRedirect('/');

        $this->assertFalse(Auth::check());
    }

    /** @test */
    public function can_unauthenticated_user_not_logout(): void
    {
        $this->post('/logout')
            ->assertRedirect('/login');

        $this->assertFalse(Auth::check());
    }
}
