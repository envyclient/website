<?php

namespace Tests\Feature\Admin;

use App\Http\Livewire\Admin\UploadAssets;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AssetsTest extends TestCase
{
    use RefreshDatabase;

    const FILE_NAME = 'assets.zip';
    const FILE_NAME_INVALID = 'assets.zip.invalid';
    const FILE_SIZE = 1000;
    const FILE_SIZE_INVALID = 6000;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->actingAs(self::admin());
    }

    /** @test */
    public function can_admin_upload_assets()
    {
        Livewire::test(UploadAssets::class)
            ->set('assets', UploadedFile::fake()->create(self::FILE_NAME, self::FILE_SIZE))
            ->call('submit')
            ->assertHasNoErrors();

        Storage::disk('public')->assertExists(self::FILE_NAME);
    }

    /** @test */
    public function can_admin_not_upload_empty_assets()
    {
        Livewire::test(UploadAssets::class)
            ->call('submit')
            ->assertHasErrors();

        Storage::disk('public')->assertMissing(self::FILE_NAME);
    }

    /** @test */
    public function can_admin_not_upload_oversize_assets()
    {
        Livewire::test(UploadAssets::class)
            ->set('assets', UploadedFile::fake()->create(self::FILE_NAME, self::FILE_SIZE_INVALID))
            ->call('submit')
            ->assertHasErrors();

        Storage::disk('public')->assertMissing(self::FILE_NAME);
    }

    /** @test */
    public function can_admin_not_upload_non_zip_assets()
    {
        Livewire::test(UploadAssets::class)
            ->set('assets', UploadedFile::fake()->create(self::FILE_NAME_INVALID, self::FILE_SIZE))
            ->call('submit')
            ->assertHasErrors();

        Storage::disk('public')->assertMissing(self::FILE_NAME_INVALID);
    }
}
