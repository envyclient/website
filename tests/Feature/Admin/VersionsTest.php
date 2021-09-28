<?php

namespace Tests\Feature\Admin;

use App\Http\Livewire\Admin\Version\UploadVersion;
use App\Jobs\EncryptVersionJob;
use App\Models\Version;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

// TODO
class VersionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_admin_upload_version()
    {
        Storage::persistentFake('local');
        Queue::fake();

        $this->actingAs(self::admin());

        /*'name' => ['required', 'string', 'max:30', 'unique:versions'],
        'changelog' => ['required', 'string', 'max:65535'],
        'main' => ['required', 'string', 'max:255'],
        'beta' => ['nullable', 'bool'],
        'version' => ['required', 'file', 'max:25000'],*/

        Livewire::test(UploadVersion::class)
            ->set('name', 'Envy 1.0')
            ->set('changelog', '+test\n+test1')
            ->set('main', 'com.envyclient.Main')
            ->set('beta', false)
            ->set('version', UploadedFile::fake()->create('version.jar', 1000))
            ->call('submit')
            ->assertHasNoErrors();

        Queue::assertPushed(EncryptVersionJob::class);

        Storage::disk('local')
            ->assertExists('versions/' . md5(1) . '.jar');

        $this->assertDatabaseCount(Version::class, 1);
    }
}
