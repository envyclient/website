<?php

namespace Tests\Feature\Auth\Passwords;

use App\Http\Livewire\Auth\Passwords\Reset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class ResetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_view_password_reset_page()
    {
        $user = self::user();

        $token = Str::random(16);

        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        $this->get(route('password.reset', [
            'email' => $user->email,
            'token' => $token,
        ]))
            ->assertSuccessful()
            ->assertSee($user->email)
            ->assertSeeLivewire(Reset::class);
    }

    /** @test */
    public function can_reset_password()
    {
        $user = self::user();

        $token = Str::random(16);

        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        Livewire::test(Reset::class, [
            'token' => $token,
        ])
            ->set('email', $user->email)
            ->set('password', 'new-password')
            ->set('passwordConfirmation', 'new-password')
            ->call('submit');

        $this->assertTrue(auth()->attempt([
            'email' => $user->email,
            'password' => 'new-password',
        ]));
    }

    /** @test */
    public function token_is_required()
    {
        Livewire::test(Reset::class, [
            'token' => '',
        ])
            ->call('submit')
            ->assertHasErrors(['token' => 'required']);
    }

    /** @test */
    public function email_is_required()
    {
        Livewire::test(Reset::class, [
            'token' => Str::random(16),
        ])
            ->set('email', '')
            ->call('submit')
            ->assertHasErrors(['email' => 'required']);
    }

    /** @test */
    public function email_is_valid_email()
    {
        Livewire::test(Reset::class, [
            'token' => Str::random(16),
        ])
            ->set('email', 'email')
            ->call('submit')
            ->assertHasErrors(['email' => 'email']);
    }

    /** @test */
    public function password_is_required()
    {
        Livewire::test(Reset::class, [
            'token' => Str::random(16),
        ])
            ->set('password', '')
            ->call('submit')
            ->assertHasErrors(['password' => 'required']);
    }

    /** @test */
    public function password_is_minimum_of_eight_characters()
    {
        Livewire::test(Reset::class, [
            'token' => Str::random(16),
        ])
            ->set('password', 'secret')
            ->call('submit')
            ->assertHasErrors(['password' => 'min']);
    }

    /** @test */
    public function password_matches_password_confirmation()
    {
        Livewire::test(Reset::class, [
            'token' => Str::random(16),
        ])
            ->set('password', 'new-password')
            ->set('passwordConfirmation', 'not-new-password')
            ->call('submit')
            ->assertHasErrors(['password' => 'same']);
    }
}
