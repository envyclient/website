<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_user_upload_cape()
    {
        Storage::fake('public');

        // create non admin user
        $user = User::factory()->create([
            'admin' => 0,
        ]);

        // subscribe to free plan
        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => 1,
            'billing_agreement_id' => null,
            'end_date' => now()
        ]);

        $this->actingAs($user)
            ->post(route('capes.store'), [
                'cape' => UploadedFile::fake()->image('cape.png'),
            ])
            ->assertRedirect();

        // Assert the file was stored...
        $cape = User::find(1)->cape;
        $this->assertTrue($cape !== null);
        //Storage::disk('public')->assertExists("capes/$cape");
    }
}
