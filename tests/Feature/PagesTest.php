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
        $this->get(route('home'))
            ->assertRedirect('/login');
    }

    /** @test */
    public function can_guest_not_see_security()
    {
        $this->get(route('home.profile'))
            ->assertRedirect('/login');
    }

    /** @test */
    public function can_guest_not_see_subscription()
    {
        $this->get(route('home.subscription'))
            ->assertRedirect('/login');
    }

    /** @test */
    public function can_user_see_dashboard()
    {
        $user = User::factory()->create([
            'admin' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('home'))
            ->assertOk();
    }

    /** @test */
    public function can_user_see_security()
    {
        $user = User::factory()->create([
            'admin' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('home'))
            ->assertOk();
    }

    /** @test */
    public function can_user_see_subscription()
    {
        $user = User::factory()->create([
            'admin' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('home'))
            ->assertOk();
    }

}
