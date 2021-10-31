<?php

namespace Feature\API;

use App\Models\Config;
use App\Models\Version;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConfigsTest extends TestCase
{
    use RefreshDatabase;

    const VALID_CONFIG = [
        'name' => 'name',
        'version' => '1.0',
        'data' => '[]',
        'public' => true,
    ];

    const INVALID_CONFIG = [
        'name' => 'name',
        'version' => '1.1',
        'data' => '',
        'public' => true,
    ];

    protected function setUp(): void
    {
        parent::setUp();
        Version::factory()->create([
            'name' => 'Envy 1.0',
        ]);
    }

    /** @test */
    public function can_unauthenticated_user_not_interact_with_configs()
    {
        $this->getJson(route('configs.index'))
            ->assertUnauthorized();
    }

    /** @test */
    public function can_unsubscribed_user_not_interact_with_configs()
    {
        $this->actingAs(self::user(), 'api')
            ->getJson(route('configs.index'))
            ->assertRedirect(route('home'));

        $this->assertDatabaseCount(Config::class, 0);
    }

    /** @test */
    public function can_user_list_configs()
    {
        $user = self::subscribedUser();
        Config::factory()->count(10)->create([
            'user_id' => $user->id,
            'version_id' => 1,
        ]);

        $this->actingAs($user, 'api')
            ->getJson(route('configs.index'))
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertOk();
    }

    /** @test */
    public function can_user_search_configs()
    {
        $user = self::subscribedUser();
        Config::factory()->count(10)->create([
            'user_id' => $user->id,
            'version_id' => 1,
        ]);

        $this->actingAs($user, 'api')
            ->getJson(route('configs.index') . '?search=' . urlencode('random config'))
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonCount(0, 'data')
            ->assertOk();
    }

    /** @test */
    public function can_user_create_valid_config()
    {
        $this->actingAs(self::subscribedUser(), 'api')
            ->postJson(route('configs.store'), self::VALID_CONFIG)
            ->assertCreated();

        $this->assertDatabaseCount(Config::class, 1);
    }

    /** @test */
    public function can_user_not_create_invalid_config()
    {
        $this->actingAs(self::subscribedUser(), 'api')
            ->postJson(route('configs.store'), self::INVALID_CONFIG)
            ->assertJsonValidationErrors(['version', 'data']);

        $this->assertDatabaseCount(Config::class, 0);
    }

    /** @test */
    public function can_user_not_go_over_config_limit()
    {
        $user = $this->subscribedUser();
        Config::factory()->count($user->subscription->plan->config_limit)->create([
            'user_id' => $user->id,
            'version_id' => 1.
        ]);

        $this->actingAs($user, 'api')
            ->postJson(route('configs.store'), self::VALID_CONFIG)
            ->assertStatus(406);

        $this->assertDatabaseCount(Config::class, $user->subscription->plan->config_limit);

    }

    /** @test */
    public function can_user_update_config()
    {
        $user = self::subscribedUser();
        $config = Config::factory()->create([
            'user_id' => $user->id,
            'version_id' => 1,
            'public' => true,
        ]);

        Version::factory()->create([
            'name' => 'Envy 1.1'
        ]);

        $this->actingAs($user, 'api')
            ->putJson(route('configs.update', $config->id), [
                'name' => 'new name',
                'version' => '1.1',
                'data' => '{}',
                'public' => false,
            ])
            ->assertOk();

        $config->refresh();
        $this->assertEquals('new name', $config->name);
        $this->assertEquals(2, $config->version_id);
        $this->assertEquals('{}', $config->data);
        $this->assertFalse($config->public);
    }

    /** @test */
    public function can_user_delete_own_config()
    {
        $user = self::subscribedUser();
        $config = Config::factory()->create([
            'user_id' => $user->id,
            'version_id' => 1,
        ]);

        $this->actingAs($user, 'api')
            ->deleteJson(route('configs.destroy', $config->id))
            ->assertOk();

        $this->assertModelMissing($config);
    }

    /** @test */
    public function can_user_not_delete_others_config()
    {
        $config = Config::factory()->create([
            'user_id' => self::user()->id,
            'version_id' => 1,
        ]);

        $this->actingAs(self::subscribedUser(), 'api')
            ->deleteJson(route('configs.destroy', $config->id))
            ->assertNotFound();

        $this->assertModelExists($config);
    }

    /** @test */
    public function can_user_favorite_config()
    {
        $user = self::subscribedUser();
        $config = Config::factory()->create([
            'user_id' => self::user()->id,
            'version_id' => 1,
        ]);

        $this->actingAs($user, 'api')
            ->putJson(route('configs.favorite', $config->id), self::INVALID_CONFIG)
            ->assertOk();

        $this->assertCount(1, $user->favorites);
    }

    /** @test */
    public function can_user_not_favorite_own_config()
    {
        $user = self::subscribedUser();
        $config = Config::factory()->create([
            'user_id' => $user->id,
            'version_id' => 1,
        ]);

        $this->actingAs($user, 'api')
            ->putJson(route('configs.favorite', $config->id), self::INVALID_CONFIG)
            ->assertForbidden();

        $this->assertCount(0, $user->favorites);
    }

    /** @test */
    public function can_user_get_own_configs()
    {
        $user = self::subscribedUser();
        Config::factory()->count(10)->create([
            'user_id' => $user->id,
            'version_id' => 1,
        ]);

        $this->actingAs($user, 'api')
            ->getJson(route('configs.search'))
            ->assertJsonCount(10);
    }

    /** @test */
    public function can_user_get_others_configs()
    {
        $user = self::subscribedUser();
        $otherUser = self::subscribedUser();

        // create 10 configs for auth user
        Config::factory()->count(10)->create([
            'user_id' => $user->id,
            'version_id' => 1,
        ]);

        // create 9 public configs
        Config::factory()->count(9)->create([
            'user_id' => $otherUser->id,
            'version_id' => 1,
            'public' => true,
        ]);

        // create 1 private config
        Config::factory()->count(1)->create([
            'user_id' => $otherUser->id,
            'version_id' => 1,
            'public' => false,
        ]);

        $this->actingAs($user, 'api')
            ->getJson(route('configs.search', $otherUser->name))
            ->assertJsonStructure(['data', 'links', 'meta'])
            ->assertJsonCount(5, 'data'); // should only show 5 configs per page
    }
}
