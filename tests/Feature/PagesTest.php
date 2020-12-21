<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
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

    /** @test */
    public function can_non_admin_not_see_users()
    {
        $user = User::factory()->create([
            'admin' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('admin.users'))
            ->assertRedirect(RouteServiceProvider::HOME);
    }

    /** @test */
    public function can_non_admin_not_see_versions()
    {
        $user = User::factory()->create([
            'admin' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('admin.versions'))
            ->assertRedirect(RouteServiceProvider::HOME);
    }

    /** @test */
    public function can_admin_see_users()
    {
        $user = User::factory()->create([
            'admin' => 1,
        ]);

        $this->actingAs($user)
            ->get(route('admin.versions'))
            ->assertOk();
    }

    /** @test */
    public function can_admin_see_versions()
    {
        $user = User::factory()->create([
            'admin' => 1,
        ]);

        $this->actingAs($user)
            ->get(route('admin.users'))
            ->assertOk();
    }
}
