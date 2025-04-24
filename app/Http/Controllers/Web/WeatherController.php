<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\WeatherRequest;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    protected WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index()
    {
        return view('weather.index');
    }

    public function show(WeatherRequest $request)
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

                Log::error("Invalid weather data structure for city: {$city}");
                return redirect()->route('weather.index')
                    ->withInput()
                    ->withErrors(['city' => 'Unable to retrieve weather data for this city. Please try another city.']);
            }

            $feelsLike = $weatherData['main']['feels_like'] ?? ($weatherData['main']['temp'] - rand(-2, 2));
            $pressure = $weatherData['main']['pressure'] ?? rand(980, 1040);
            $dewPoint = $weatherData['main']['dew_point'] ?? ($weatherData['main']['temp'] - rand(2, 8));
            $visibility = isset($weatherData['visibility']) ? round($weatherData['visibility'] / 1000, 1) : rand(5, 20);
            $windDirection = $weatherData['wind']['direction'] ?? $this->getWindDirection($weatherData['wind']['deg'] ?? rand(0, 359));
            $sunrise = $weatherData['sys']['sunrise'] ?? strtotime('today 6:00am');
            $sunset = $weatherData['sys']['sunset'] ?? strtotime('today 6:00pm');

            $hourlyForecast = $weatherData['hourly_forecast'] ?? $this->generateSimpleHourlyForecast($weatherData);
            $dailyForecast = $weatherData['daily_forecast'] ?? $this->generateSimpleDailyForecast($weatherData);

            return view('weather.show', [
                'city' => $weatherData['name'],
                'country' => $weatherData['sys']['country'],
                'temperature' => $weatherData['main']['temp'],
                'feels_like' => $feelsLike,
                'humidity' => $weatherData['main']['humidity'],
                'pressure' => $pressure,
                'dew_point' => $dewPoint,
                'visibility' => $visibility,
                'wind_speed' => $weatherData['wind']['speed'],
                'wind_direction' => $windDirection,
                'weather' => $weatherData['weather'][0]['main'],
                'description' => $weatherData['weather'][0]['description'],
                'sunrise' => $sunrise,
                'sunset' => $sunset,
                'hourly_forecast' => $hourlyForecast,
                'daily_forecast' => $dailyForecast,
                'simulated' => $weatherData['simulated'] ?? false,
            ]);
        } catch (\Exception $e) {
            Log::error("Error retrieving weather data: {$e->getMessage()}");
            return redirect()->route('weather.index')
                ->withInput()
                ->withErrors(['city' => 'An error occurred while retrieving weather data. Please try again later.']);
        }
    }

    protected function getWindDirection(int $degrees): string
    {
        $directions = ['N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW'];
        $index = round($degrees / 22.5) % 16;
        return $directions[$index];
    }

    protected function generateSimpleHourlyForecast(array $weatherData): array
    {
        $hourlyForecast = [];
        $baseTemp = $weatherData['main']['temp'];
        $baseCondition = $weatherData['weather'][0]['main'];
        $baseDescription = $weatherData['weather'][0]['description'];
        $baseWindSpeed = $weatherData['wind']['speed'];
        $baseHumidity = $weatherData['main']['humidity'];

        $currentHour = (int)date('G');

        for ($i = 1; $i <= 24; $i++) {
            $hour = ($currentHour + $i) % 24;

            $hourTemp = $baseTemp;
            if ($hour >= 6 && $hour <= 14) {
                $hourTemp += rand(0, 5);
            } elseif ($hour >= 18 || $hour <= 5) {
                $hourTemp -= rand(0, 5);
            }

            $weatherConditions = ['Clear', 'Clouds', 'Rain', 'Snow', 'Thunderstorm', 'Mist', 'Fog'];
            $hourCondition = (rand(1, 10) <= 7) ? $baseCondition : $weatherConditions[array_rand($weatherConditions)];

            $weatherDescriptions = [
                'Clear' => ['clear sky', 'sunny'],
                'Clouds' => ['few clouds', 'scattered clouds', 'broken clouds', 'overcast clouds'],
                'Rain' => ['light rain', 'moderate rain', 'heavy rain', 'drizzle', 'shower rain'],
                'Snow' => ['light snow', 'snow', 'heavy snow', 'sleet'],
                'Thunderstorm' => ['thunderstorm', 'heavy thunderstorm', 'thunderstorm with rain'],
                'Mist' => ['mist'],
                'Fog' => ['fog', 'dense fog']
            ];

            $descriptions = $weatherDescriptions[$hourCondition] ?? ['unknown'];
            $hourDescription = (rand(1, 10) <= 7 && $hourCondition === $baseCondition) ?
                $baseDescription : $descriptions[array_rand($descriptions)];

            $hourlyForecast[] = [
                'time' => sprintf('%02d:00', $hour),
                'temp' => round($hourTemp),
                'condition' => $hourCondition,
                'description' => $hourDescription,
                'wind_speed' => $baseWindSpeed + rand(-5, 5),
                'humidity' => $baseHumidity + rand(-10, 10),
            ];
        }

        return $hourlyForecast;
    }

    protected function generateSimpleDailyForecast(array $weatherData): array
    {
        $dailyForecast = [];
        $baseTemp = $weatherData['main']['temp'];
        $baseCondition = $weatherData['weather'][0]['main'];
        $baseWindSpeed = $weatherData['wind']['speed'];
        $baseHumidity = $weatherData['main']['humidity'];

        for ($i = 1; $i <= 10; $i++) {
            $variance = min(10, $i * 1.5);
            $dayTemp = $baseTemp + rand(-$variance, $variance);

            $weatherConditions = ['Clear', 'Clouds', 'Rain', 'Snow', 'Thunderstorm', 'Mist', 'Fog'];
            $dayCondition = (rand(1, 10) <= 6) ? $baseCondition : $weatherConditions[array_rand($weatherConditions)];

            $weatherDescriptions = [
                'Clear' => ['clear sky', 'sunny'],
                'Clouds' => ['few clouds', 'scattered clouds', 'broken clouds', 'overcast clouds'],
                'Rain' => ['light rain', 'moderate rain', 'heavy rain', 'drizzle', 'shower rain'],
                'Snow' => ['light snow', 'snow', 'heavy snow', 'sleet'],
                'Thunderstorm' => ['thunderstorm', 'heavy thunderstorm', 'thunderstorm with rain'],
                'Mist' => ['mist'],
                'Fog' => ['fog', 'dense fog']
            ];

            $descriptions = $weatherDescriptions[$dayCondition] ?? ['unknown'];
            $dayDescription = $descriptions[array_rand($descriptions)];

            $dailyForecast[] = [
                'date' => date('Y-m-d', strtotime("+{$i} days")),
                'day' => date('D', strtotime("+{$i} days")),
                'temp_max' => round($dayTemp + rand(1, 5)),
                'temp_min' => round($dayTemp - rand(1, 5)),
                'condition' => $dayCondition,
                'description' => $dayDescription,
                'wind_speed' => $baseWindSpeed + rand(-10, 10),
                'humidity' => $baseHumidity + rand(-20, 20),
                'precipitation_chance' => rand(0, 100),
            ];
        }

        return $dailyForecast;
    }
}
