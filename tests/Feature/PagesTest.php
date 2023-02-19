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
    public function can_guest_see_index(): void
    {
        $this->get(route('index'))
            ->assertOk();
    }

    /** @test */
    public function can_guest_see_terms(): void
    {
        $this->get(route('terms'))
            ->assertOk();
    }

    /*
    * User Pages
    */

    /** @test */
    public function can_guest_not_see_home(): void
    {
        $this->get(route('home'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function can_guest_not_see_profile(): void
    {
        $this->get(route('home.profile'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function can_guest_not_see_subscription(): void
    {
        $this->get(route('home.subscription'))
            ->assertRedirect(route('login'));
    }

    /*
    * Admin Pages
    */

    /** @test */
    public function can_guest_not_see_users(): void
    {
        $this->get(route('admin.users'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function can_guest_not_see_versions(): void
    {
        $this->get(route('admin.versions'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function can_guest_not_see_launcher_and_assets(): void
    {
        $this->get(route('admin.launcher'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function can_guest_not_see_loader(): void
    {
        $this->get(route('admin.loader'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function can_guest_not_see_referral_code(): void
    {
        $this->get(route('admin.referrals'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function can_guest_not_see_license_requests(): void
    {
        $this->get(route('admin.license-requests'))
            ->assertRedirect(route('login'));
    }

    /*
    * User Pages
    */

    /** @test */
    public function can_user_see_home(): void
    {
        $this->actingAs(self::user())
            ->get(route('home'))
            ->assertOk();
    }

    /** @test */
    public function can_user_see_profile(): void
    {
        $this->actingAs(self::user())
            ->get(route('home.profile'))
            ->assertOk();
    }

    /** @test */
    public function can_user_see_subscription(): void
    {
        $this->actingAs(self::user())
            ->get(route('home.subscription'))
            ->assertOk();
    }

    /*
    * Admin Pages
    */

    /** @test */
    public function can_user_not_see_users(): void
    {
        $this->actingAs(self::user())
            ->get(route('admin.users'))
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function can_user_not_see_versions(): void
    {
        $this->actingAs(self::user())
            ->get(route('admin.versions'))
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function can_user_not_see_launcher_and_assets(): void
    {
        $this->actingAs(self::user())
            ->get(route('admin.launcher'))
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function can_user_not_see_loader(): void
    {
        $this->actingAs(self::user())
            ->get(route('admin.loader'))
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function can_user_not_see_referral_codes(): void
    {
        $this->actingAs(self::user())
            ->get(route('admin.referrals'))
            ->assertRedirect(route('home'));
    }

    /** @test */
    public function can_user_not_see_license_requests(): void
    {
        $this->actingAs(self::user())
            ->get(route('admin.license-requests'))
            ->assertRedirect(route('home'));
    }

    /*
    * Admin Pages
    */

    /** @test */
    public function can_admin_see_users(): void
    {
        $this->actingAs(self::admin())
            ->get(route('admin.users'))
            ->assertOk();
    }

    /** @test */
    public function can_admin_see_versions(): void
    {
        $this->actingAs(self::admin())
            ->get(route('admin.versions'))
            ->assertOk();
    }

    /** @test */
    public function can_admin_see_launcher_and_assets(): void
    {
        $this->actingAs(self::admin())
            ->get(route('admin.launcher'))
            ->assertOk();
    }

    /** @test */
    public function can_admin_see_loader(): void
    {
        $this->actingAs(self::admin())
            ->get(route('admin.loader'))
            ->assertOk();
    }

    /** @test */
    public function can_admin_see_referral_codes(): void
    {
        $this->actingAs(self::admin())
            ->get(route('admin.referrals'))
            ->assertOk();
    }

    /** @test */
    public function can_admin_see_license_requests(): void
    {
        $this->actingAs(self::admin())
            ->get(route('admin.license-requests'))
            ->assertOk();
    }
}
