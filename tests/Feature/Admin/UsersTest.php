<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

// TODO
class UsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_admin_reset_user_hwid()
    {
        $user = User::factory()->create([
            'admin' => 1,
        ]);

        $this->actingAs($user)
            ->assertTrue($user->hwid !== null);

        Livewire::test(UsersTable::class)
            ->call('resetUserHWID', $user->id);

        $this->assertTrue(User::find(1)->hwid === null);
    }

    /** @test */
    public function can_admin_ban_user()
    {
        $user = User::factory()->create([
            'admin' => 1,
        ]);

        $this->actingAs($user)
            ->assertFalse($user->banned);

        Livewire::test(UsersTable::class)
            ->call('banUser', $user->id);

        $this->assertTrue(User::find(1)->banned);
    }
}
