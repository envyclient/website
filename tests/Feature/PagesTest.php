<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesTest extends TestCase
{
    use RefreshDatabase;

    /*
     * Guest Pages
     */

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

    /*
    * User Pages
    */

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

    /*
    * Admin Pages
    */

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
    public function can_guest_not_see_launcher_and_loader()
    {
        $this->get(route('admin.launcher-loader'))
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

    /*
    * User Pages
    */

    /** @test */
    public function can_user_see_home()
    {
        $this->actingAs(self::user())
            ->get(route('home'))
            ->assertOk();
    }

    /** @test */
    public function can_user_see_profile()
    {
        $this->actingAs(self::user())
            ->get(route('home.profile'))
            ->assertOk();
    }

    /** @test */
    public function can_user_see_subscription()
    {
        $this->actingAs(self::user())
            ->get(route('home.subscription'))
            ->assertOk();
    }

    /*
    * Admin Pages
    */

    /** @test */
    public function can_user_not_see_users()
    {
        $this->actingAs(self::user())
            ->get(route('admin.users'))
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function can_user_not_see_versions()
    {
        $this->actingAs(self::user())
            ->get(route('admin.versions'))
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function can_user_not_see_launcher_and_loader()
    {
        $this->actingAs(self::user())
            ->get(route('admin.launcher-loader'))
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function can_user_not_see_referral_codes()
    {
        $this->actingAs(self::user())
            ->get(route('admin.referrals'))
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function can_user_not_see_license_requests()
    {
        $this->actingAs(self::user())
            ->get(route('admin.license-requests'))
            ->assertRedirect(route('home'));
    }

    /*
    * Admin Pages
    */

    /** @test */
    public function can_admin_see_users()
    {
        $this->actingAs(self::admin())
            ->get(route('admin.users'))
            ->assertOk();
    }

    /** @test */
    public function can_admin_see_versions()
    {
        $this->actingAs(self::admin())
            ->get(route('admin.versions'))
            ->assertOk();
    }

    /** @test */
    public function can_admin_see_launcher_and_loader()
    {
        $this->actingAs(self::admin())
            ->get(route('admin.launcher-loader'))
            ->assertOk();
    }

    /** @test */
    public function can_admin_see_referral_codes()
    {
        $this->actingAs(self::admin())
            ->get(route('admin.referrals'))
            ->assertOk();
    }

    /** @test */
    public function can_admin_see_license_requests()
    {
        $this->actingAs(self::admin())
            ->get(route('admin.license-requests'))
            ->assertOk();
    }

}
