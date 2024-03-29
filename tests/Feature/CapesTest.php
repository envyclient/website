<?php

namespace Tests\Feature;

use App\Enums\SubscriptionStatus;
use App\Http\Livewire\User\Home\UploadCape;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Livewire\Testing\TestableLivewire;
use Tests\TestCase;

class CapesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('s3_public');
    }

    /** @test */
    public function can_user_not_upload_invalid_cape(): void
    {
        // create non-admin user
        $user = self::user();

        // upload a invalid cape
        $this->upload_cape(
            $user,
            UploadedFile::fake()->image('cape.png', 1, 1)
        )->assertHasErrors('cape');

        // assert that cape does exist
        Storage::disk('s3_public')->assertMissing('capes/'.$user->hash.'.png');
    }

    /** @test */
    public function can_user_upload_valid_cape(): void
    {
        // create non-admin user
        $user = self::user();

        // upload a valid cape
        $this->upload_cape(
            $user,
            UploadedFile::fake()->image('cape.png', 2048, 1024)
        )->assertHasNoErrors();

        // assert that cape exists
        $this->assertGreaterThan(0, strlen($user->cape));
        Storage::disk('s3_public')->assertExists('capes/'.$user->hash.'.png');
    }

    /** @test */
    public function can_user_reset_cape(): void
    {
        // create non-admin user
        $user = self::user();

        // upload a valid cape
        $this->upload_cape(
            $user,
            UploadedFile::fake()->image('cape.png', 2048, 1024)
        )->assertHasNoErrors();

        // reset cape
        Livewire::test(UploadCape::class)
            ->call('resetCape')
            ->assertHasNoErrors();

        // assert that cape does not exist
        Storage::disk('s3_public')->assertMissing('capes/'.$user->hash.'.png');
    }

    private function upload_cape(User $user, File $file): TestableLivewire
    {
        // login as the user
        $this->actingAs($user);

        // subscribe to free plan
        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => 1, // free plan
            'status' => SubscriptionStatus::ACTIVE->value,
            'end_date' => now()->addMonth(),
        ]);

        // upload a valid cape
        return Livewire::test(UploadCape::class)
            ->set('cape', $file)
            ->call('submit');
    }
}
