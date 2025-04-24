<?php

namespace Tests\Unit\Services;

use App\Models\City;
use App\Services\WeatherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test getting simulated weather data.
     */
    public function test_get_simulated_weather_data(): void
    {
        // Create a weather service instance
        $weatherService = new WeatherService();

        // Get weather data for a city
        $weatherData = $weatherService->getCurrentWeather('London');

        // Assert the structure of the weather data
        $this->assertArrayHasKey('name', $weatherData);
        $this->assertArrayHasKey('main', $weatherData);
        $this->assertArrayHasKey('temp', $weatherData['main']);
        $this->assertArrayHasKey('humidity', $weatherData['main']);
        $this->assertArrayHasKey('weather', $weatherData);
        $this->assertArrayHasKey('wind', $weatherData);
        $this->assertArrayHasKey('speed', $weatherData['wind']);
        $this->assertArrayHasKey('sys', $weatherData);
        $this->assertArrayHasKey('country', $weatherData['sys']);
        // The simulated key might not be present if using the real API
        if (isset($weatherData['simulated'])) {
            $this->assertTrue($weatherData['simulated']);
        }
    }

    /**
     * Test checking if a weather condition is met.
     */
    public function test_is_condition_met(): void
    {
        // Mock the WeatherService to return a fixed weather data
        $weatherService = $this->getMockBuilder(WeatherService::class)
            ->onlyMethods(['getCurrentWeather'])
            ->getMock();

        $weatherService->method('getCurrentWeather')
            ->willReturn([
                'name' => 'London',
                'main' => ['temp' => 5, 'humidity' => 70],
                'weather' => [['main' => 'Rain']],
                'wind' => ['speed' => 10],
                'sys' => ['country' => 'GB'],
            ]);

        // Test temperature_below condition (should be met)
        $result = $weatherService->isConditionMet('London', 'temperature_below', 10);
        $this->assertTrue($result['met']);
        $this->assertEquals(5, $result['current_value']);

        // Test temperature_below condition (should not be met)
        $result = $weatherService->isConditionMet('London', 'temperature_below', 0);
        $this->assertFalse($result['met']);

        // Test temperature_above condition (should be met)
        $result = $weatherService->isConditionMet('London', 'temperature_above', 0);
        $this->assertTrue($result['met']);

        // Test temperature_above condition (should not be met)
        $result = $weatherService->isConditionMet('London', 'temperature_above', 10);
        $this->assertFalse($result['met']);

        // Test rain condition (should be met)
        $result = $weatherService->isConditionMet('London', 'rain', null);
        $this->assertTrue($result['met']);

        // Test snow condition (should not be met)
        $result = $weatherService->isConditionMet('London', 'snow', null);
        $this->assertFalse($result['met']);

        // Test wind_speed_above condition (should be met)
        $result = $weatherService->isConditionMet('London', 'wind_speed_above', 5);
        $this->assertTrue($result['met']);
        $this->assertEquals(10, $result['current_value']);

        // Test wind_speed_above condition (should not be met)
        $result = $weatherService->isConditionMet('London', 'wind_speed_above', 15);
        $this->assertFalse($result['met']);
    }
}
