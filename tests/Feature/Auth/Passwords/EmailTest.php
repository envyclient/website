<?php

namespace Tests\Feature\Auth\Passwords;

use App\Http\Livewire\Auth\Passwords\Email;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EmailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_view_password_request_page()
    {
        $this->get(route('password.request'))
            ->assertSuccessful()
            ->assertSeeLivewire(Email::class);
    }

    /** @test */
    public function a_user_must_enter_an_email_address()
    {
        Livewire::test(Email::class)
            ->call('submit')
            ->assertHasErrors(['email' => 'required']);
    }

    /** @test */
    public function a_user_must_enter_a_valid_email_address()
    {
        Livewire::test(Email::class)
            ->set('email', 'email')
            ->call('submit')
            ->assertHasErrors(['email' => 'email']);
    }

    /** @test */
    public function a_user_who_enters_a_valid_email_address_will_get_sent_an_email()
    {
        $user = self::user();

        Livewire::test(Email::class)
            ->set('email', $user->email)
            ->call('submit')
            ->assertSessionMissing('status');

        $this->assertDatabaseHas('password_resets', [
            'email' => $user->email,
        ]);
    }
}
