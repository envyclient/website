<?php

namespace Tests\Feature\Auth;

use App\Http\Livewire\Auth\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_view_login_page()
    {
        $this->get(route('login'))
            ->assertSuccessful()
            ->assertSeeLivewire(Login::class);
    }

    /** @test */
    public function is_redirected_if_already_logged_in()
    {
        $user = self::user();

        $this->be($user);

        $this->get(route('login'))
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function a_user_can_login()
    {
        $user = self::user();

        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'password')
            ->call('submit');

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function is_redirected_to_the_home_page_after_login()
    {
        $user = self::user();

        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'password')
            ->call('submit')
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function email_is_required()
    {
        self::user();

        Livewire::test(Login::class)
            ->set('password', 'password')
            ->call('submit')
            ->assertHasErrors(['email' => 'required']);
    }

    /** @test */
    public function email_must_be_valid_email()
    {
        self::user();

        Livewire::test(Login::class)
            ->set('email', 'invalid-email')
            ->set('password', 'password')
            ->call('submit')
            ->assertHasErrors(['email' => 'email']);
    }

    /** @test */
    public function password_is_required()
    {
        $user = self::user();

        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->call('submit')
            ->assertHasErrors(['password' => 'required']);
    }

    /** @test */
    public function bad_login_attempt_shows_message()
    {
        $user = self::user();

        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'bad-password')
            ->call('submit')
            ->assertHasErrors('email');

        $this->assertFalse(auth()->check());
    }
}
