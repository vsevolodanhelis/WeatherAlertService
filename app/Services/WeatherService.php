<?php

namespace App\Services;

use App\Models\City;
use App\Models\WeatherSubscription;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    protected string $baseUrl = 'https://api.openweathermap.org/data/2.5';
    protected ?string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openweathermap.key');
    }

    public function getCurrentWeather(string $cityName): array
    {
        if (empty($cityName)) {
            Log::error('Empty city name provided to getCurrentWeather');
            throw new \InvalidArgumentException('City name cannot be empty');
        }

        $cityName = trim($cityName);

        if (strlen($cityName) < 2) {
            Log::error("City name too short: {$cityName}");
            throw new \InvalidArgumentException('City name must be at least 2 characters long');
        }

        if (strlen($cityName) > 100) {
            Log::error("City name too long: {$cityName}");
            throw new \InvalidArgumentException('City name cannot exceed 100 characters');
        }

        $cacheKey = "weather:{$cityName}";

        if (Cache::has($cacheKey)) {
            Log::info("Returning cached weather data for {$cityName}");
            return Cache::get($cacheKey);
        }

        try {
            if (empty($this->apiKey)) {
                return $this->getSimulatedWeatherData($cityName);
            }

            if (!preg_match('/^[a-zA-Z\s\-\.]+$/', $cityName)) {
                Log::warning("Invalid city name format: {$cityName}");
                return $this->getSimulatedWeatherData($cityName);
            }

            $response = Http::withoutVerifying()
                ->timeout(5)
                ->retry(2, 1000)
                ->get("{$this->baseUrl}/weather", [
                    'q' => $cityName,
                    'appid' => $this->apiKey,
                    'units' => 'metric',
                ]);

            if ($response->successful()) {
                $data = $response->json();

                if (!isset($data['name']) || !isset($data['main']) || !isset($data['weather']) || !isset($data['wind']) || !isset($data['sys'])) {
                    Log::warning("Invalid API response structure for city: {$cityName}");
                    return $this->getSimulatedWeatherData($cityName);
                }

                Cache::put($cacheKey, $data, now()->addMinutes(30));

                return $data;
            }

            if ($response->status() === 404) {
                Log::warning("City not found: {$cityName}");
            } else {
                Log::error("API error for city {$cityName}: {$response->status()} - {$response->body()}");
            }

            return $this->getSimulatedWeatherData($cityName);
        } catch (\Exception $e) {
            Log::error('Error fetching weather data: ' . $e->getMessage());
            return $this->getSimulatedWeatherData($cityName);
        }
    }

    protected function getSimulatedWeatherData(string $cityName): array
    {
        if (empty($cityName) || !is_string($cityName)) {
            $cityName = 'Unknown';
            Log::warning('Invalid city name provided to getSimulatedWeatherData, using "Unknown"');
        }

        $cityName = trim($cityName);
        if (strlen($cityName) < 2) {
            $cityName = 'Unknown';
            Log::warning('City name too short for getSimulatedWeatherData, using "Unknown"');
        }

        try {
            City::firstOrCreate(
                ['name' => $cityName, 'country_code' => 'XX'],
                ['latitude' => 0, 'longitude' => 0]
            );
        } catch (\Exception $e) {
            Log::error("Error storing city in database: {$e->getMessage()}");
        }

        $temperature = rand(-10, 35);
        $feelsLike = $temperature + rand(-3, 3);
        $humidity = rand(30, 90);
        $windSpeed = rand(0, 30);
        $pressure = rand(980, 1040);
        $visibility = rand(5, 20);
        $dewPoint = $temperature - rand(2, 8);

        $windDirections = ['N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW'];
        $windDirection = $windDirections[array_rand($windDirections)];

        $weatherConditions = ['Clear', 'Clouds', 'Rain', 'Snow', 'Thunderstorm', 'Mist', 'Fog'];
        $weatherCondition = $weatherConditions[array_rand($weatherConditions)];

        $weatherDescriptions = [
            'Clear' => ['clear sky', 'sunny'],
            'Clouds' => ['few clouds', 'scattered clouds', 'broken clouds', 'overcast clouds'],
            'Rain' => ['light rain', 'moderate rain', 'heavy rain', 'drizzle', 'shower rain'],
            'Snow' => ['light snow', 'snow', 'heavy snow', 'sleet'],
            'Thunderstorm' => ['thunderstorm', 'heavy thunderstorm', 'thunderstorm with rain'],
            'Mist' => ['mist'],
            'Fog' => ['fog', 'dense fog']
        ];

        $descriptions = $weatherDescriptions[$weatherCondition];
        $weatherDescription = $descriptions[array_rand($descriptions)];

        $month = (int)date('m');
        if ($month >= 12 || $month <= 2) {
            $temperature = rand(-10, 10);
            $feelsLike = $temperature - rand(1, 5);
            if (rand(1, 100) <= 40) {
                $weatherCondition = 'Snow';
                $weatherDescription = $weatherDescriptions['Snow'][array_rand($weatherDescriptions['Snow'])];
            }
        } elseif ($month >= 6 && $month <= 8) {
            $temperature = rand(15, 35);
            $feelsLike = $temperature + rand(0, 5);
            if (rand(1, 100) <= 70) {
                $weatherCondition = (rand(1, 100) <= 70) ? 'Clear' : 'Rain';
                if ($weatherCondition === 'Clear') {
                    $weatherDescription = $weatherDescriptions['Clear'][array_rand($weatherDescriptions['Clear'])];
                } else {
                    $weatherDescription = $weatherDescriptions['Rain'][array_rand($weatherDescriptions['Rain'])];
                }
            }
        }

        $hourlyForecast = [];
        $baseTemp = $temperature;
        $baseCondition = $weatherCondition;
        $baseDescription = $weatherDescription;

        for ($i = 1; $i <= 24; $i++) {
            $hourTemp = $baseTemp;
            if ($i >= 6 && $i <= 14) {
                $hourTemp += rand(0, 5);
            } elseif ($i >= 18 || $i <= 5) {
                $hourTemp -= rand(0, 5);
            }

            $hourCondition = (rand(1, 10) <= 7) ? $baseCondition : $weatherConditions[array_rand($weatherConditions)];
            $hourDescription = (rand(1, 10) <= 7) ? $baseDescription :
                $weatherDescriptions[$hourCondition][array_rand($weatherDescriptions[$hourCondition])];

            $hourlyForecast[] = [
                'time' => sprintf('%02d:00', ($i % 24)),
                'temp' => $hourTemp,
                'condition' => $hourCondition,
                'description' => $hourDescription,
                'wind_speed' => $windSpeed + rand(-5, 5),
                'humidity' => $humidity + rand(-10, 10),
            ];
        }

        $dailyForecast = [];
        $baseTemp = $temperature;
        $baseCondition = $weatherCondition;

        for ($i = 1; $i <= 10; $i++) {
            $variance = min(10, $i * 1.5);
            $dayTemp = $baseTemp + rand(-$variance, $variance);
            $dayCondition = (rand(1, 10) <= 6) ? $baseCondition : $weatherConditions[array_rand($weatherConditions)];
            $dayDescription = $weatherDescriptions[$dayCondition][array_rand($weatherDescriptions[$dayCondition])];

            $dailyForecast[] = [
                'date' => date('Y-m-d', strtotime("+{$i} days")),
                'day' => date('D', strtotime("+{$i} days")),
                'temp_max' => $dayTemp + rand(1, 5),
                'temp_min' => $dayTemp - rand(1, 5),
                'condition' => $dayCondition,
                'description' => $dayDescription,
                'wind_speed' => $windSpeed + rand(-10, 10),
                'humidity' => $humidity + rand(-20, 20),
                'precipitation_chance' => rand(0, 100),
            ];
        }

        $airQualityIndex = rand(1, 10);
        $pollutionLevel = rand(10, 200);
        $uvIndex = rand(1, 12);
        $relativeHumidity = rand(20, 90);
        $pressureChange = rand(-20, 20);

        return [
            'name' => $cityName,
            'main' => [
                'temp' => $temperature,
                'feels_like' => $feelsLike,
                'humidity' => $humidity,
                'pressure' => $pressure,
                'dew_point' => $dewPoint,
            ],
            'weather' => [
                [
                    'main' => $weatherCondition,
                    'description' => $weatherDescription,
                ]
            ],
            'wind' => [
                'speed' => $windSpeed,
                'direction' => $windDirection,
            ],
            'visibility' => $visibility * 1000,
            'sys' => [
                'country' => 'XX',
                'sunrise' => strtotime('today 6:00am'),
                'sunset' => strtotime('today 6:00pm'),
            ],
            'air_quality' => $airQualityIndex,
            'pollution' => $pollutionLevel,
            'uv_index' => $uvIndex,
            'relative_humidity' => $relativeHumidity,
            'pressure_change' => $pressureChange,
            'hourly_forecast' => $hourlyForecast,
            'daily_forecast' => $dailyForecast,
            'simulated' => true,
        ];
    }

    public function isConditionMet(string $cityName, string $conditionType, ?float $conditionValue = null): array
    {
        if (empty($cityName)) {
            Log::error('Empty city name provided to isConditionMet');
            return [
                'met' => false,
                'current_value' => null,
                'weather' => [],
                'error' => 'City name cannot be empty',
            ];
        }

        if (empty($conditionType)) {
            Log::error('Empty condition type provided to isConditionMet');
            return [
                'met' => false,
                'current_value' => null,
                'weather' => [],
                'error' => 'Condition type cannot be empty',
            ];
        }

        try {
            $weather = $this->getCurrentWeather($cityName);
            $result = false;
            $currentValue = null;
            $message = '';

            if (!in_array($conditionType, array_keys(WeatherSubscription::CONDITION_TYPES))) {
                Log::warning("Invalid condition type: {$conditionType}");
                return [
                    'met' => false,
                    'current_value' => null,
                    'weather' => $weather,
                    'error' => 'Invalid condition type',
                ];
            }

            $requiresValue = in_array($conditionType, ['temperature_below', 'temperature_above', 'wind_speed_above']);
            if ($requiresValue && $conditionValue === null) {
                Log::warning("Missing condition value for {$conditionType}");
                return [
                    'met' => false,
                    'current_value' => null,
                    'weather' => $weather,
                    'error' => 'Missing condition value',
                ];
            }

            if (!isset($weather['main']) || !isset($weather['weather']) || !isset($weather['wind'])) {
                Log::error("Invalid weather data structure for condition check: {$cityName}");
                return [
                    'met' => false,
                    'current_value' => null,
                    'weather' => $weather,
                    'error' => 'Invalid weather data structure',
                ];
            }

            if (!isset($weather['weather'][0]['main'])) {
                Log::error("Missing weather condition in data for: {$cityName}");
                return [
                    'met' => false,
                    'current_value' => null,
                    'weather' => $weather,
                    'error' => 'Missing weather condition data',
                ];
            }

            switch ($conditionType) {
                case 'temperature_below':
                    if (!isset($weather['main']['temp'])) {
                        Log::error("Missing temperature data for: {$cityName}");
                        return [
                            'met' => false,
                            'current_value' => null,
                            'weather' => $weather,
                            'error' => 'Missing temperature data',
                        ];
                    }

                    $currentValue = $weather['main']['temp'];
                    if ($conditionValue < -100 || $conditionValue > 100) {
                        Log::warning("Unusual temperature threshold: {$conditionValue}°C");
                    }

                    $result = $currentValue < $conditionValue;
                    $message = $result
                        ? "Temperature is {$currentValue}°C, which is below your threshold of {$conditionValue}°C"
                        : "Temperature is {$currentValue}°C, which is not below your threshold of {$conditionValue}°C";
                    break;

                case 'temperature_above':
                    if (!isset($weather['main']['temp'])) {
                        Log::error("Missing temperature data for: {$cityName}");
                        return [
                            'met' => false,
                            'current_value' => null,
                            'weather' => $weather,
                            'error' => 'Missing temperature data',
                        ];
                    }

                    $currentValue = $weather['main']['temp'];
                    if ($conditionValue < -100 || $conditionValue > 100) {
                        Log::warning("Unusual temperature threshold: {$conditionValue}°C");
                    }

                    $result = $currentValue > $conditionValue;
                    $message = $result
                        ? "Temperature is {$currentValue}°C, which is above your threshold of {$conditionValue}°C"
                        : "Temperature is {$currentValue}°C, which is not above your threshold of {$conditionValue}°C";
                    break;

                case 'rain':
                    $weatherMain = strtolower($weather['weather'][0]['main']);
                    $result = $weatherMain === 'rain' || $weatherMain === 'drizzle';
                    $message = $result
                        ? "It's currently raining in {$cityName}"
                        : "It's not raining in {$cityName}";
                    break;

                case 'snow':
                    $result = strtolower($weather['weather'][0]['main']) === 'snow';
                    $message = $result
                        ? "It's currently snowing in {$cityName}"
                        : "It's not snowing in {$cityName}";
                    break;

                case 'wind_speed_above':
                    if (!isset($weather['wind']['speed'])) {
                        Log::error("Missing wind speed data for: {$cityName}");
                        return [
                            'met' => false,
                            'current_value' => null,
                            'weather' => $weather,
                            'error' => 'Missing wind speed data',
                        ];
                    }

                    $currentValue = $weather['wind']['speed'];
                    if ($conditionValue < 0 || $conditionValue > 200) {
                        Log::warning("Unusual wind speed threshold: {$conditionValue} m/s");
                    }

                    $result = $currentValue > $conditionValue;
                    $message = $result
                        ? "Wind speed is {$currentValue} m/s, which is above your threshold of {$conditionValue} m/s"
                        : "Wind speed is {$currentValue} m/s, which is not above your threshold of {$conditionValue} m/s";
                    break;

                case 'thunderstorm':
                    $weatherMain = strtolower($weather['weather'][0]['main']);
                    $result = $weatherMain === 'thunderstorm';
                    $message = $result
                        ? "There is currently a thunderstorm in {$cityName}"
                        : "There is no thunderstorm in {$cityName}";
                    break;

                case 'tornado':
                    $weatherDescription = strtolower($weather['weather'][0]['description']);
                    $result = strpos($weatherDescription, 'tornado') !== false;
                    $message = $result
                        ? "There is a tornado warning for {$cityName}"
                        : "There is no tornado warning for {$cityName}";
                    break;

                case 'poor_air_quality':
                    $airQualityIndex = isset($weather['air_quality']) ? $weather['air_quality'] : rand(1, 10);
                    $result = $airQualityIndex > 5;
                    $message = $result
                        ? "Air quality is poor in {$cityName} with an index of {$airQualityIndex}"
                        : "Air quality is acceptable in {$cityName} with an index of {$airQualityIndex}";
                    break;

                case 'high_pollution':
                    $pollutionLevel = isset($weather['pollution']) ? $weather['pollution'] : rand(10, 200);
                    $result = $pollutionLevel > 100;
                    $message = $result
                        ? "Pollution level is high in {$cityName} at {$pollutionLevel} units"
                        : "Pollution level is acceptable in {$cityName} at {$pollutionLevel} units";
                    break;

                case 'high_uv_index':
                    $uvIndex = isset($weather['uv_index']) ? $weather['uv_index'] : rand(1, 12);
                    $result = $uvIndex >= 6 && $uvIndex < 8;
                    $message = $result
                        ? "UV index is high in {$cityName} at {$uvIndex}"
                        : "UV index is not high in {$cityName} at {$uvIndex}";
                    break;

                case 'extreme_uv_index':
                    $uvIndex = isset($weather['uv_index']) ? $weather['uv_index'] : rand(1, 12);
                    $result = $uvIndex >= 8;
                    $message = $result
                        ? "UV index is extreme in {$cityName} at {$uvIndex}"
                        : "UV index is not extreme in {$cityName} at {$uvIndex}";
                    break;

                case 'high_humidity':
                    $humidity = isset($weather['main']['humidity']) ? $weather['main']['humidity'] : rand(20, 90);
                    $result = $humidity > 70;
                    $message = $result
                        ? "Humidity is high in {$cityName} at {$humidity}%"
                        : "Humidity is not high in {$cityName} at {$humidity}%";
                    break;

                case 'low_humidity':
                    $humidity = isset($weather['main']['humidity']) ? $weather['main']['humidity'] : rand(20, 90);
                    $result = $humidity < 30;
                    $message = $result
                        ? "Humidity is low in {$cityName} at {$humidity}%"
                        : "Humidity is not low in {$cityName} at {$humidity}%";
                    break;

                case 'pressure_increase':
                    $pressureChange = isset($weather['pressure_change']) ? $weather['pressure_change'] : rand(-20, 20);
                    $result = $pressureChange > 10;
                    $message = $result
                        ? "Atmospheric pressure is rapidly increasing in {$cityName} ({$pressureChange} hPa change)"
                        : "Atmospheric pressure is not rapidly increasing in {$cityName} ({$pressureChange} hPa change)";
                    break;

                case 'pressure_decrease':
                    $pressureChange = isset($weather['pressure_change']) ? $weather['pressure_change'] : rand(-20, 20);
                    $result = $pressureChange < -10;
                    $message = $result
                        ? "Atmospheric pressure is rapidly decreasing in {$cityName} ({$pressureChange} hPa change)"
                        : "Atmospheric pressure is not rapidly decreasing in {$cityName} ({$pressureChange} hPa change)";
                    break;

                default:
                    $message = "Unsupported condition type: {$conditionType}";
                    break;
            }

            return [
                'met' => $result,
                'current_value' => $currentValue,
                'message' => $message,
                'weather' => $weather,
            ];
        } catch (\Exception $e) {
            Log::error("Error checking weather condition: {$e->getMessage()}");
            return [
                'met' => false,
                'current_value' => null,
                'weather' => [],
                'error' => "Error checking weather condition: {$e->getMessage()}",
            ];
        }
    }
}
