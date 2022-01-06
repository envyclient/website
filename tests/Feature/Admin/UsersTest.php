<?php

namespace Tests\Feature\Admin;

use App\Http\Livewire\Admin\User\EditUserModal;
use App\Http\Livewire\Admin\User\UsersTable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    const VALID_NAME = 'this_is_a_valid_name';
    const INVALID_NAME = 'this name is not allowed';

    /** @test */
    public function can_admin_update_user_name()
    {
        $user = self::user();

        $this->actingAs(self::admin());

        Livewire::test(EditUserModal::class)
            ->call('edit', $user->id)
            ->set('user.name', self::VALID_NAME)
            ->call('save')
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertEquals(self::VALID_NAME, $user->name);
    }

    /** @test */
    public function can_admin_not_update_invalid_user_name()
    {
        $user = self::user();
        $originalName = $user->name;

        $this->actingAs(self::admin());

        Livewire::test(EditUserModal::class)
            ->call('edit', $user->id)
            ->set('user.name', self::INVALID_NAME)
            ->call('save')
            ->assertHasErrors('user.name');

        $user->refresh();
        $this->assertEquals($originalName, $user->name);
    }

    /** @test */
    public function can_admin_update_user_subscription()
    {
        $user = self::user();

        $this->actingAs(self::admin());

        // give the user a subscription
        Livewire::test(UsersTable::class)
            ->call('updateFreeSubscription', $user->id, false)
            ->assertHasNoErrors();

        $this->assertModelExists($user->subscription);

        // remove subscription from user
        Livewire::test(UsersTable::class)
            ->call('updateFreeSubscription', $user->id, true)
            ->assertHasNoErrors();

        $this->assertSoftDeleted($user->subscription);
    }

    /** @test */
    public function can_admin_reset_user_hwid()
    {
        $user = self::user();

        $this->actingAs(self::admin())
            ->assertNotNull($user->hwid);

        Livewire::test(UsersTable::class)
            ->call('resetUserHWID', $user->id)
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertNull($user->hwid);
    }

    /** @test */
    public function can_admin_update_user_ban()
    {
        $user = self::user();

        $this->actingAs(self::admin())
            ->assertFalse($user->banned);

        // ban the user
        Livewire::test(UsersTable::class)
            ->call('updateUserBan', $user->id, true)
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertTrue($user->banned);

        // unban the user
        Livewire::test(UsersTable::class)
            ->call('updateUserBan', $user->id, false)
            ->assertHasNoErrors();

        $user->refresh();
        $this->assertFalse($user->banned);
    }
}
