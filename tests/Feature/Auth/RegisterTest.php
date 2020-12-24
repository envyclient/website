<?php

namespace Tests\Feature\Auth;

use App\Http\Livewire\Auth\Register;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_register_page_be_seen()
    {
        $this->get('/register')
            ->assertStatus(200);
    }

    /** @test */
    public function can_user_register()
    {
        Livewire::test(Register::class)
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->set('passwordConfirmation', 'password')
            ->call('register')
            ->assertRedirect(RouteServiceProvider::HOME);

        $this->assertAuthenticated();
        $this->assertTrue(
            User::where('email', 'test@example.com')->exists()
        );
    }

}
