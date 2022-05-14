<?php

namespace Tests\Unit\Api;

use App\Models\LicenceKey;
use Carbon\Carbon;
use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ValidateLicenceKeyTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Passport::actingAsClient(
            Client::factory()->create(),
            ['manage-key']
        );
    }

    /** @test */
    public function it_validates_an_existing_key(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $licenceKey = LicenceKey::factory()->create([
            'valid_until_at' => '2019-01-01',
        ]);

        $response = $this->get("/api/validate/{$licenceKey->key}");

        $response->assertStatus(200);
    }

    /** @test */
    public function it_fails_if_the_key_doesnt_exist(): void
    {
        $response = $this->get('/api/validate/123');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_fails_if_the_key_is_not_valid_anymore(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $licenceKey = LicenceKey::factory()->create([
            'valid_until_at' => '2017-01-01',
        ]);

        $response = $this->get("/api/validate/{$licenceKey->key}");

        $response->assertStatus(410);
    }
}
