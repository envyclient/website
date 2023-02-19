<?php

namespace Tests\Feature\Auth;

use App\Http\Livewire\Auth\Verify;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Tests\TestCase;

class VerifyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_view_verification_page()
    {
        $user = self::user(['email_verified_at' => null]);

        auth()->login($user);

        $this->get(route('verification.notice'))
            ->assertSuccessful()
            ->assertSeeLivewire(Verify::class);
    }

    /** @test */
    public function can_resend_verification_email()
    {
        $user = self::user();

        Livewire::actingAs($user);

        Livewire::test(Verify::class)
            ->call('submit')
            ->assertEmitted('resent');
    }

    /** @test */
    public function can_verify()
    {
        Event::fake();

        $user = self::user(['email_verified_at' => null]);

        auth()->login($user);

        $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(config('auth.verification.expire', 60)), [
            'id' => $user->getKey(),
            'hash' => sha1($user->getEmailForVerification()),
        ]);

        $this->get($url)
            ->assertRedirect(route('home'));

        $this->assertTrue($user->hasVerifiedEmail());

        Event::assertDispatched(Verified::class);
    }
}
