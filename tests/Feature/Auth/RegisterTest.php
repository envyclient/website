<?php

namespace Tests\Feature\Auth;

use App\Http\Livewire\Auth\Register;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function registration_page_contains_livewire_component()
    {
        $this->get(route('register'))
            ->assertSuccessful()
            ->assertSeeLivewire(Register::class);
    }

    /** @test */
    public function is_redirected_if_already_logged_in()
    {
        $user = self::user();

        $this->be($user);

        $this->get(route('register'))
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function a_user_can_register()
    {
        Event::fake();

        Livewire::test(Register::class)
            ->set('name', 'Tall_Stack')
            ->set('email', 'tallstack@example.com')
            ->set('password', 'password')
            ->set('passwordConfirmation', 'password')
            ->call('submit')
            ->assertRedirect(route('home'));

        $this->assertDatabaseHas(User::class, [
            'email' => 'tallstack@example.com',
        ]);

        Event::assertDispatched(Registered::class);
    }

    /** @test */
    public function name_is_required()
    {
        Livewire::test(Register::class)
            ->set('name', '')
            ->call('submit')
            ->assertHasErrors(['name' => 'required']);
    }

    /** @test */
    public function email_is_required()
    {
        Livewire::test(Register::class)
            ->set('email', '')
            ->call('submit')
            ->assertHasErrors(['email' => 'required']);
    }

    /** @test */
    public function email_is_valid_email()
    {
        Livewire::test(Register::class)
            ->set('email', 'tallstack')
            ->call('submit')
            ->assertHasErrors(['email' => 'email']);
    }

    /** @test */
    public function email_hasnt_been_taken_already()
    {
        self::user(['email' => 'tallstack@example.com']);

        Livewire::test(Register::class)
            ->set('email', 'tallstack@example.com')
            ->call('submit')
            ->assertHasErrors(['email' => 'unique']);
    }

    /** @test */
    public function see_email_hasnt_already_been_taken_validation_message_as_user_types()
    {
        self::user(['email' => 'tallstack@example.com']);

        Livewire::test(Register::class)
            ->set('email', 'smallstack@gmail.com')
            ->assertHasNoErrors()
            ->set('email', 'tallstack@example.com')
            ->call('submit')
            ->assertHasErrors(['email' => 'unique']);
    }

    /** @test */
    public function password_is_required()
    {
        Livewire::test(Register::class)
            ->set('password', '')
            ->set('passwordConfirmation', 'password')
            ->call('submit')
            ->assertHasErrors(['password' => 'required']);
    }

    /** @test */
    public function password_is_minimum_of_eight_characters()
    {
        Livewire::test(Register::class)
            ->set('password', 'secret')
            ->set('passwordConfirmation', 'secret')
            ->call('submit')
            ->assertHasErrors(['password' => 'min']);
    }

    /** @test */
    public function password_matches_password_confirmation()
    {
        Livewire::test(Register::class)
            ->set('email', 'tallstack@example.com')
            ->set('password', 'password')
            ->set('passwordConfirmation', 'not-password')
            ->call('submit')
            ->assertHasErrors(['password' => 'same']);
    }
}
