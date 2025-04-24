<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WeatherRequest;
use App\Services\WeatherService;
use Illuminate\Http\JsonResponse;

class WeatherController extends Controller
{
    protected WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function getCurrentWeather(WeatherRequest $request): JsonResponse
    {
        try {
            $city = $request->input('city');
            $weatherData = $this->weatherService->getCurrentWeather($city);

            if (!isset($weatherData['name']) ||
                !isset($weatherData['sys']['country']) ||
                !isset($weatherData['main']['temp']) ||
                !isset($weatherData['main']['humidity']) ||
                !isset($weatherData['wind']['speed']) ||
                !isset($weatherData['weather'][0]['main']) ||
                !isset($weatherData['weather'][0]['description'])) {

                \Illuminate\Support\Facades\Log::error('Invalid weather data structure', ['data' => $weatherData]);

                return response()->json([
                    'error' => 'Unable to retrieve complete weather data',
                    'message' => 'The weather service returned incomplete data. Please try again later.',
                ], 500);
            }

            return response()->json([
                'data' => [
                    'city' => $weatherData['name'],
                    'country' => $weatherData['sys']['country'],
                    'temperature' => $weatherData['main']['temp'],
                    'humidity' => $weatherData['main']['humidity'],
                    'wind_speed' => $weatherData['wind']['speed'],
                    'weather' => $weatherData['weather'][0]['main'],
                    'description' => $weatherData['weather'][0]['description'],
                    'simulated' => $weatherData['simulated'] ?? false,
                ],
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'error' => 'Invalid request',
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in getCurrentWeather: ' . $e->getMessage());

            return response()->json([
                'error' => 'Server error',
                'message' => 'An unexpected error occurred. Please try again later.',
            ], 500);
        }
    }
}
