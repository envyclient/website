<?php

namespace Tests\Feature;

use App\Http\Livewire\User\Home\LicenseRequests;
use App\Models\LicenseRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LicenseRequestTest extends TestCase
{
    use RefreshDatabase;

    const VALID_CHANNEL = 'https://www.youtube.com/c/LaravelPHP';
    const INVALID_CHANNEL = 'https://www.youtube.com/channel/UCPGP3hEz8oXnGK_nrBvyBbQ';
    const INVALID_URL = 'https://www.google.com';

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(self::user());
    }

    /** @test */
    public function can_user_create_license_request(): void
    {
        Livewire::test(LicenseRequests::class)
            ->set('channel', self::VALID_CHANNEL)
            ->call('submit');

        $this->assertDatabaseCount(LicenseRequest::class, 1);
    }

    /** @test */
    public function can_user_not_create_license_request_with_invalid_url(): void
    {
        Livewire::test(LicenseRequests::class)
            ->set('channel', self::INVALID_URL)
            ->call('submit')
            ->assertHasErrors('channel');

        $this->assertDatabaseCount(LicenseRequest::class, 0);
    }

    /** @test */
    public function can_user_not_create_license_request_with_less_than_200_subs(): void
    {
        Livewire::test(LicenseRequests::class)
            ->set('channel', self::INVALID_CHANNEL)
            ->call('submit')
            ->assertHasErrors('channel');

        $this->assertDatabaseCount(LicenseRequest::class, 0);
    }

    /** @test */
    public function can_user_not_create_2_active_license_requests(): void
    {
        Livewire::test(LicenseRequests::class)
            ->set('channel', self::VALID_CHANNEL)
            ->call('submit');

        $this->assertDatabaseCount(LicenseRequest::class, 1);

        Livewire::test(LicenseRequests::class)
            ->set('channel', self::VALID_CHANNEL)
            ->call('submit')
            ->assertHasErrors('channel');

        $this->assertDatabaseCount(LicenseRequest::class, 1);
    }
}
