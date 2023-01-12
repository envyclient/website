<?php

namespace Tests\Feature;

use App\Http\Livewire\Admin\Referral\CreateReferralCode;
use App\Models\ReferralCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class ReferralCodeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_user_not_use_invalid_referral_code(): void
    {
        $user = self::user();

        $this->actingAs($user)
            ->post(route('users.referral-code'), [
                'referral-code' => 'random_code',
            ])
            ->assertSessionHasErrors()
            ->assertRedirect();

        $this->assertNull($user->referral_code_id);
    }

    /** @test */
    public function can_user_use_valid_referral_code(): void
    {
        $user = self::user();
        $referralCode = ReferralCode::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)
            ->post(route('users.referral-code'), [
                'referral-code' => $referralCode->code,
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $this->assertNotNull($user->referral_code_id);
    }

    /** @test */
    public function can_user_not_use_more_than_1_valid_referral_code(): void
    {
        $user = self::user();
        $referralCodes = ReferralCode::factory()->count(2)->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);

        // use first referral code
        $this->post(route('users.referral-code'), [
            'referral-code' => $referralCodes[0]->code,
        ])
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        // check if referral code was sued properly
        $this->assertNotNull($user->referral_code_id);

        // use another referral code
        $this->post(route('users.referral-code'), [
            'referral-code' => $referralCodes[1]->code,
        ])
            ->assertSessionHas('error')
            ->assertRedirect();
    }

    /** @test */
    public function can_admin_not_create_invalid_referral_code(): void
    {
        Livewire::test(CreateReferralCode::class)
            ->set('user', 'random_name')
            ->set('code', Str::random(16))
            ->call('submit')
            ->assertHasErrors(['user', 'code']);

        $this->assertDatabaseCount(ReferralCode::class, 0);
    }

    /** @test */
    public function can_admin_create_valid_referral_code(): void
    {
        $user = self::admin();

        Livewire::test(CreateReferralCode::class)
            ->set('user', $user->name)
            ->set('code', Str::random(15))
            ->call('submit');

        $this->assertDatabaseCount(ReferralCode::class, 1);
    }
}
