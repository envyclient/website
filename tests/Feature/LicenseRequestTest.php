<?php

namespace Tests\Feature;

use App\Http\Livewire\User\Home\MediaRequests;
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

    /** @test */
    public function can_user_create_license_request()
    {
        $this->actingAs($this->user());

        Livewire::test(MediaRequests::class)
            ->set('channel', self::VALID_CHANNEL)
            ->call('submit');

        $this->assertEquals(1, LicenseRequest::count());
    }

    /** @test */
    public function can_user_not_create_license_request_with_invalid_url()
    {
        $this->actingAs($this->user());

        Livewire::test(MediaRequests::class)
            ->set('channel', self::INVALID_URL)
            ->call('submit')
            ->assertHasErrors('channel');

        $this->assertEquals(0, LicenseRequest::count());
    }

    /** @test */
    public function can_user_not_create_license_request_with_less_than_200_subs()
    {
        $this->actingAs($this->user());

        Livewire::test(MediaRequests::class)
            ->set('channel', self::INVALID_CHANNEL)
            ->call('submit')
            ->assertHasErrors('channel');

        $this->assertEquals(0, LicenseRequest::count());
    }

    /** @test */
    public function can_user_not_create_two_active_license_requests()
    {
        $this->actingAs($this->user());

        Livewire::test(MediaRequests::class)
            ->set('channel', self::VALID_CHANNEL)
            ->call('submit');

        $this->assertEquals(1, LicenseRequest::count());

        Livewire::test(MediaRequests::class)
            ->set('channel', self::VALID_CHANNEL)
            ->call('submit')
            ->assertHasErrors('channel');

        $this->assertEquals(1, LicenseRequest::count());
    }
}
