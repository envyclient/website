<?php

namespace Tests\Feature\Admin;

use App\Http\Livewire\UsersTable;
use App\Http\Livewire\VersionsTable;
use App\Models\User;
use App\Models\Version;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class VersionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_non_admin_not_see_versions_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get(route('admin.versions'))
            ->assertRedirect(RouteServiceProvider::HOME);
    }

    /** @test */
    public function can_admin_see_versions_page()
    {
        $user = User::factory()->create([
            'admin' => 1,
        ]);

        $this->actingAs($user)
            ->get(route('admin.versions'))
            ->assertStatus(200);
    }

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

    /** @test */
    public function can_admin_delete_version()
    {
        $this->can_admin_upload_version();

        $version = Version::find(1);

        Livewire::test(VersionsTable::class)
            ->call('deleteVersion', 1);

        $this->assertFalse($version->exists());
        Storage::disk('local')
            ->assertMissing($version->version)
            ->assertMissing($version->assets);
    }
}
