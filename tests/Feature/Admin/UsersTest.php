<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_non_admin_not_see_users()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get(route('admin.users'))
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
}
