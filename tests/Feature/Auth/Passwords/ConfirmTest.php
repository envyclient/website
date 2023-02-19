<?php

namespace Tests\Feature\Auth\Passwords;

use App\Http\Livewire\Auth\Passwords\Confirm;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Tests\TestCase;

class ConfirmTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::get('/must-be-confirmed', function () {
            return 'You must be confirmed to see this page.';
        })->middleware(['web', 'password.confirm']);
    }

    /** @test */
    public function a_user_must_confirm_their_password_before_visiting_a_protected_page()
    {
        $user = self::user();

        $this->be($user);

        $this->get('/must-be-confirmed')
            ->assertRedirect(route('password.confirm'));

        $this->followingRedirects()
            ->get('/must-be-confirmed')
            ->assertSeeLivewire(Confirm::class);
    }

    /** @test */
    public function a_user_must_enter_a_password_to_confirm_it()
    {
        Livewire::test(Confirm::class)
            ->call('submit')
            ->assertHasErrors(['password' => 'required']);
    }

    /** @test */
    public function a_user_must_enter_their_own_password_to_confirm_it()
    {
        self::user();

        Livewire::test(Confirm::class)
            ->set('password', 'not-password')
            ->call('submit')
            ->assertHasErrors(['password' => 'current_password']);
    }

    /** @test */
    public function a_user_who_confirms_their_password_will_get_redirected()
    {
        $user = self::user();

        $this->be($user);

        $this->withSession(['url.intended' => '/must-be-confirmed']);

        Livewire::test(Confirm::class)
            ->set('password', 'password')
            ->call('submit')
            ->assertRedirect('/must-be-confirmed');
    }
}
