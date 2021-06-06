<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_guest_see_index()
    {
        $this->get(route('index'))
            ->assertOk();
    }

    /** @test */
    public function can_guest_see_terms()
    {
        $this->get(route('terms'))
            ->assertOk();
    }

    /** @test */
    public function can_guest_not_see_home()
    {
        $this->get(route('home'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function can_guest_not_see_profile()
    {
        $this->get(route('home.profile'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function can_guest_not_see_subscription()
    {
        $this->get(route('home.subscription'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function can_guest_not_see_users()
    {
        $this->get(route('admin.users'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function can_guest_not_see_versions()
    {
        $this->get(route('admin.versions'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function can_guest_not_see_referral_code()
    {
        $this->get(route('admin.referrals'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function can_guest_not_see_license_requests()
    {
        $this->get(route('admin.license-requests'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function can_user_see_home()
    {
        $this->actingAs($this->user())
            ->get(route('home'))
            ->assertOk();
    }

    /** @test */
    public function can_user_see_profile()
    {
        $this->actingAs($this->user())
            ->get(route('home.profile'))
            ->assertOk();
    }

    /** @test */
    public function can_user_see_subscription()
    {
        $this->actingAs($this->user())
            ->get(route('home.subscription'))
            ->assertOk();
    }

    /** @test */
    public function can_user_not_see_users()
    {
        $this->actingAs($this->user())
            ->get(route('admin.users'))
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function can_user_not_see_versions()
    {
        $this->actingAs($this->user())
            ->get(route('admin.versions'))
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function can_user_not_see_referral_codes()
    {
        $this->actingAs($this->user())
            ->get(route('admin.referrals'))
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function can_user_not_see_license_requests()
    {
        $this->actingAs($this->user())
            ->get(route('admin.license-requests'))
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function can_admin_see_users()
    {
        $this->actingAs($this->admin())
            ->get(route('admin.users'))
            ->assertOk();
    }

    /** @test */
    public function can_admin_see_versions()
    {
        $this->actingAs($this->admin())
            ->get(route('admin.versions'))
            ->assertOk();
    }

    /** @test */
    public function can_admin_see_referral_codes()
    {
        $this->actingAs($this->admin())
            ->get(route('admin.referrals'))
            ->assertOk();
    }

    /** @test */
    public function can_admin_see_license_requests()
    {
        $this->actingAs($this->admin())
            ->get(route('admin.license-requests'))
            ->assertOk();
    }

}
