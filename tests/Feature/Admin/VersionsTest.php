<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Version;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

// TODO
class VersionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_admin_upload_version()
    {
        Storage::fake('local');

        $user = User::factory()->create([
            'admin' => 1,
        ]);

        $this->actingAs($user)
            ->post(route('admin.versions.upload'), [
                'name' => 'Beta 1',
                'beta' => true,
                'version' => UploadedFile::fake()->create('version.exe', 1000),
                'assets' => UploadedFile::fake()->create('assets.jar', 1000),
                'changelog' => '+test\n+test1',
            ])
            ->assertRedirect();

        // Assert the file was stored...
        $version = Version::find(1);
        $this->assertTrue($version->exists());
        Storage::disk('local')
            ->assertExists($version->version)
            ->assertExists($version->assets);
    }
}
