<?php

namespace Tests\Feature\Api;

use App\Services\WeatherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class WeatherControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Test getting weather data for a city.
     */
    public function test_get_weather_data_for_city(): void
    {
        // Mock the WeatherService
        $weatherService = Mockery::mock(WeatherService::class);
        $weatherService->shouldReceive('getCurrentWeather')
            ->once()
            ->with('London')
            ->andReturn([
                'name' => 'London',
                'sys' => ['country' => 'GB'],
                'main' => ['temp' => 15.5, 'humidity' => 70],
                'wind' => ['speed' => 5.5],
                'weather' => [['main' => 'Clouds', 'description' => 'scattered clouds']],
            ]);

        $this->app->instance(WeatherService::class, $weatherService);

        // Make the request
        $response = $this->getJson('/api/weather?city=London');

        // Assert the response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'city',
                    'country',
                    'temperature',
                    'humidity',
                    'wind_speed',
                    'weather',
                    'description',
                ],
            ])
            ->assertJson([
                'data' => [
                    'city' => 'London',
                    'country' => 'GB',
                    'temperature' => 15.5,
                    'humidity' => 70,
                    'wind_speed' => 5.5,
                    'weather' => 'Clouds',
                    'description' => 'scattered clouds',
                ],
            ]);
    }

    /**
     * Test validation error when city is missing.
     */
    public function test_validation_error_when_city_is_missing(): void
    {
        $response = $this->getJson('/api/weather');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['city']);
    }
}
