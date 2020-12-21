<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_unauthenticated_user_not_see_dashboard()
    {
        $this->get(route('dashboard'))
            ->assertRedirect('/login');
    }

    /** @test */
    public function can_authenticated_user_see_dashboard()
    {
        $user = User::factory()->create([
            'admin' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk();
    }

    /*public function can_upload_cape()
    {
        Storage::fake('local');

        $user = User::factory()->create([
            'admin' => 0,
        ]);

        $this->actingAs($user)
            ->post(route('users.upload-cape'), [
                'cape' => UploadedFile::fake()->image('cape.jpg'),
            ])
            ->assertRedirect();

        // Assert the file was stored...
        $this->assertTrue($user->cape !== null);
        Storage::disk('local')->assertExists("capes/$user->cape");
    }*/
}
