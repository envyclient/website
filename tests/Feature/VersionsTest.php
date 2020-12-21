<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Version;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VersionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_non_admin_not_see_versions_page()
    {
        $user = User::factory()->create([
            'admin' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('admin.versions'))
            ->assertRedirect(RouteServiceProvider::HOME);
    }

    /** @test */
    public function can_admin_see_versions_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get(route('admin.versions'))
            ->assertStatus(200);
    }

    /** @test */
    public function can_upload_version()
    {
        Storage::fake('local');

        $user = User::factory()->create();
        $this->actingAs($user)
            ->post(route('admin.versions.upload'), [
                'name' => 'Beta 1',
                'beta' => true,
                'version' => UploadedFile::fake()->create('version.exe', 1000),
                'changelog' => '+test\n+test1',
            ])
            ->assertRedirect();

        // Assert the file was stored...
        $version = Version::find(1);
        $this->assertTrue($version->exists());
        Storage::disk('local')->assertExists("versions/$version->file");
    }
}
