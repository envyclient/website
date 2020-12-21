<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_guest_see_index()
    {
        $this->get(route('index'))
            ->assertOk();
    }

    /** @test */
    public function can_guest_not_see_dashboard()
    {
        $this->get(route('dashboard'))
            ->assertRedirect('/login');
    }

    /** @test */
    public function can_user_see_dashboard()
    {
        $user = User::factory()->create([
            'admin' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk();
    }

    /** @test */
    public function can_user_see_security()
    {
        $user = User::factory()->create([
            'admin' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk();
    }

    /** @test */
    public function can_user_see_subscription()
    {
        $user = User::factory()->create([
            'admin' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk();
    }

}
