<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TranslationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_translation_can_be_created()
    {
        $user = User::factory()->create();

        $token = $user->createToken('TestToken')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ])->postJson('/api/translations/create', [
            'locale' => 'en',
            'tag' => 'web',
            'key' => 'welcome_message',
            'value' => 'Welcome to our app',
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'status' => true,
                     'message' => 'Translation created successfully',
                     'data' => [
                         'key' => 'welcome_message',
                         'value' => 'Welcome to our app',
                         'locale' => 'en',
                         'tag' => 'web',
                     ],
                 ]);

        $this->assertDatabaseHas('translations', [
            'key' => 'welcome_message',
            'value' => 'Welcome to our app',
            'locale' => 'en',
            'tag' => 'web',
        ]);
    }
}
