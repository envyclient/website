<?php

namespace Tests\Feature\Admin;

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
    public function can_user_not_use_invalid_referral_code()
    {
        $user = $this->user();

        $this->actingAs($user)
            ->post(route('users.referral-code'), [
                'referral-code' => 'random_code'
            ])
            ->assertRedirect();

        $this->assertNull($user->referral_code_id);
    }

    /** @test */
    public function can_user_use_valid_referral_code()
    {
        $user = $this->user();
        $code = ReferralCode::create([
            'user_id' => $user->id,
            'code' => 'random_code',
        ]);

        $this->actingAs($user)
            ->post(route('users.referral-code'), [
                'referral-code' => $code->code,
            ])
            ->assertRedirect();

        $this->assertNotNull($user->referral_code_id);
    }

    /** @test */
    public function can_admin_not_create_invalid_referral_code()
    {
        Livewire::test(CreateReferralCode::class)
            ->set('user', 'random_name')
            ->set('code', Str::random(16))
            ->call('submit')
            ->assertHasErrors(['user', 'code']);

        $this->assertEquals(0, ReferralCode::count());
    }

    /** @test */
    public function can_admin_create_valid_referral_code()
    {
        $user = $this->admin();

        Livewire::test(CreateReferralCode::class)
            ->set('user', $user->name)
            ->set('code', Str::random(15))
            ->call('submit');

        $this->assertEquals(1, ReferralCode::count());
    }
}
