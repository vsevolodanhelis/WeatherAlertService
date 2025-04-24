<?php

namespace Tests\Feature\Api;

use App\Models\City;
use App\Models\User;
use App\Models\WeatherSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscriptionControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test creating a new subscription.
     */
    public function test_create_subscription(): void
    {
        $data = [
            'email' => 'test@example.com',
            'city' => 'London',
            'condition_type' => 'temperature_below',
            'condition_value' => 10,
        ];

        $response = $this->postJson('/api/subscriptions', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'email',
                    'city',
                    'condition_type',
                    'condition_value',
                    'created_at',
                ],
                'message',
            ])
            ->assertJson([
                'data' => [
                    'email' => 'test@example.com',
                    'city' => 'London',
                    'condition_type' => 'temperature_below',
                    'condition_value' => 10,
                ],
                'message' => 'Subscription created successfully',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        $this->assertDatabaseHas('cities', [
            'name' => 'London',
        ]);

        $this->assertDatabaseHas('weather_subscriptions', [
            'email' => 'test@example.com',
            'condition_type' => 'temperature_below',
            'condition_value' => 10,
        ]);
    }

    /**
     * Test validation errors when creating a subscription.
     */
    public function test_validation_errors_when_creating_subscription(): void
    {
        // Missing email
        $response = $this->postJson('/api/subscriptions', [
            'city' => 'London',
            'condition_type' => 'temperature_below',
            'condition_value' => 10,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);

        // Invalid email
        $response = $this->postJson('/api/subscriptions', [
            'email' => 'invalid-email',
            'city' => 'London',
            'condition_type' => 'temperature_below',
            'condition_value' => 10,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);

        // Missing city
        $response = $this->postJson('/api/subscriptions', [
            'email' => 'test@example.com',
            'condition_type' => 'temperature_below',
            'condition_value' => 10,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['city']);

        // Invalid condition type
        $response = $this->postJson('/api/subscriptions', [
            'email' => 'test@example.com',
            'city' => 'London',
            'condition_type' => 'invalid_condition',
            'condition_value' => 10,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['condition_type']);

        // Missing condition value for temperature_below
        $response = $this->postJson('/api/subscriptions', [
            'email' => 'test@example.com',
            'city' => 'London',
            'condition_type' => 'temperature_below',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['condition_value']);
    }
}
