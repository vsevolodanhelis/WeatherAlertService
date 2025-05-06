@extends('layouts.app')

@section('title', 'Weather for ' . $city)

@section('content')
    <script>
        // Function to copy text to clipboard
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                window.notify('Link copied to clipboard!');
            }, function() {
                window.notify('Failed to copy link');
            });
        }

        // Show loading animation when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Hide loading animation after page is fully loaded
            window.showLoading(false);
        });
    </script>
    <div class="max-w-4xl mx-auto">
        <!-- Back button -->
        <div class="mb-6">
            <a href="{{ route('weather.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-500 transition-colors duration-200">
                <i class="ri-arrow-left-line mr-2"></i>
                Back to search
            </a>
        </div>

        <!-- Weather card with gradient background based on weather condition -->
        <div class="rounded-xl shadow-lg overflow-hidden mb-8">
            <!-- Card header with location info -->
            <div style="background-image: linear-gradient(to bottom right, #6366f1, #9333ea); color: white !important;" class="weather-header p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold" style="color: white !important;">{{ $city }}</h1>
                        <p style="color: white !important;">{{ $country }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-5xl" style="color: white !important;">
                            @if($weather == 'Clear')
                                <i class="ri-sun-line" style="color: white !important;"></i>
                            @elseif($weather == 'Clouds')
                                <i class="ri-cloud-line" style="color: white !important;"></i>
                            @elseif($weather == 'Rain')
                                <i class="ri-rainy-line" style="color: white !important;"></i>
                            @elseif($weather == 'Snow')
                                <i class="ri-snowy-line" style="color: white !important;"></i>
                            @elseif($weather == 'Thunderstorm')
                                <i class="ri-thunderstorms-line" style="color: white !important;"></i>
                            @elseif($weather == 'Fog' || $weather == 'Mist')
                                <i class="ri-mist-line" style="color: white !important;"></i>
                            @else
                                <i class="ri-cloud-line" style="color: white !important;"></i>
                            @endif
                        </div>
                        <p class="text-xl font-medium" style="color: white !important;">{{ ucfirst($description) }}</p>
                    </div>
                </div>
            </div>

            <!-- Card body with weather details -->
            <div class="bg-white p-6" x-data="{ unit: 'metric' }">
                <!-- Unit conversion toggle -->
                <div class="flex justify-end mb-4">
                    <div class="bg-gray-100 rounded-full p-1 inline-flex">
                        <button @click="unit = 'metric'"
                                :class="unit === 'metric' ? 'bg-indigo-600 text-white' : 'bg-transparent text-gray-600'"
                                class="px-3 py-1 rounded-full text-sm font-medium transition-colors duration-200">
                            °C
                        </button>
                        <button @click="unit = 'imperial'"
                                :class="unit === 'imperial' ? 'bg-indigo-600 text-white' : 'bg-transparent text-gray-600'"
                                class="px-3 py-1 rounded-full text-sm font-medium transition-colors duration-200">
                            °F
                        </button>
                    </div>
                </div>

                <!-- Temperature display -->
                <div class="flex justify-center items-center mb-6">
                    <div class="text-center">
                        <div class="text-6xl font-bold text-gray-800 mb-2">
                            <span x-show="unit === 'metric'">{{ $temperature }}°C</span>
                            <span x-show="unit === 'imperial'" x-cloak>{{ round($temperature * 9/5 + 32) }}°F</span>
                        </div>
                        <p class="text-gray-500">
                            Feels like
                            <span x-show="unit === 'metric'">{{ $feels_like }}°C</span>
                            <span x-show="unit === 'imperial'" x-cloak>{{ round($feels_like * 9/5 + 32) }}°F</span>
                        </p>
                    </div>
                </div>

                <!-- Current conditions summary -->
                <div class="text-center mb-8">
                    <p class="text-gray-700 text-lg">
                        {{ ucfirst($description) }}. {{ $wind_speed < 5 ? 'Light breeze' : ($wind_speed < 10 ? 'Moderate breeze' : 'Strong wind') }}.
                    </p>
                </div>

                <!-- Weather details grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                    <!-- Wind -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="ri-windy-line text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Wind</p>
                                <p class="text-gray-800 font-semibold">
                                    <span x-show="unit === 'metric'">{{ $wind_speed }} m/s</span>
                                    <span x-show="unit === 'imperial'" x-cloak>{{ round($wind_speed * 2.237, 1) }} mph</span>
                                    {{ $wind_direction }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Pressure -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="ri-compass-3-line text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Pressure</p>
                                <p class="text-gray-800 font-semibold">{{ $pressure }} hPa</p>
                            </div>
                        </div>
                    </div>

                    <!-- Humidity -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="ri-drop-line text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Humidity</p>
                                <p class="text-gray-800 font-semibold">{{ $humidity }}%</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dew Point -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="ri-temp-cold-line text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Dew Point</p>
                                <p class="text-gray-800 font-semibold">
                                    <span x-show="unit === 'metric'">{{ $dew_point }}°C</span>
                                    <span x-show="unit === 'imperial'" x-cloak>{{ round($dew_point * 9/5 + 32) }}°F</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Visibility -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="ri-eye-line text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Visibility</p>
                                <p class="text-gray-800 font-semibold">
                                    <span x-show="unit === 'metric'">{{ $visibility }} km</span>
                                    <span x-show="unit === 'imperial'" x-cloak>{{ round($visibility * 0.621, 1) }} mi</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Sunrise/Sunset -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="ri-sun-line text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Sunrise / Sunset</p>
                                <p class="text-gray-800 font-semibold">{{ date('H:i', $sunrise) }} / {{ date('H:i', $sunset) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Share weather information -->
                <div class="mb-6 text-center">
                    <p class="text-gray-600 mb-3">Share this weather information</p>
                    <div class="flex justify-center space-x-4">
                        <a href="https://twitter.com/intent/tweet?text=Weather in {{ urlencode($city) }}: {{ $temperature }}°C, {{ ucfirst($description) }}&url={{ urlencode(url()->current()) }}"
                           target="_blank"
                           class="text-black p-2 rounded-full hover:text-gray-700 transition-colors duration-200">
                            <i class="ri-twitter-x-line text-xl"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                           target="_blank"
                           class="text-black p-2 rounded-full hover:text-gray-700 transition-colors duration-200">
                            <i class="ri-facebook-fill text-xl"></i>
                        </a>
                        <a href="mailto:?subject=Weather in {{ $city }}&body=Current weather in {{ $city }}: {{ $temperature }}°C, {{ ucfirst($description) }}. Check it out: {{ url()->current() }}"
                           class="text-black p-2 rounded-full hover:text-gray-700 transition-colors duration-200">
                            <i class="ri-mail-line text-xl"></i>
                        </a>
                        <a href="https://www.instagram.com/"
                           target="_blank"
                           class="text-black p-2 rounded-full hover:text-gray-700 transition-colors duration-200">
                            <i class="ri-instagram-line text-xl"></i>
                        </a>
                    </div>
                </div>

                <!-- Action buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('subscriptions.create', ['city' => $city]) }}" class="bg-indigo-600 text-white hover:bg-indigo-700 transition-colors duration-200 font-semibold py-3 px-6 rounded-lg shadow-md flex items-center justify-center">
                        <i class="ri-notification-3-line mr-2"></i>
                        Subscribe to Alerts
                    </a>
                    <a href="{{ route('weather.index') }}" class="bg-white text-indigo-600 border border-indigo-200 font-semibold py-3 px-6 rounded-lg shadow-sm flex items-center justify-center">
                        <i class="ri-search-line mr-2"></i>
                        Check Another City
                    </a>
                </div>

                @if($simulated)
                    <div class="mt-6 bg-blue-50 text-blue-700 px-4 py-3 rounded-lg text-sm text-center">
                        <i class="ri-information-line mr-1"></i>
                        Note: This is simulated weather data for demonstration purposes.
                    </div>
                @endif
            </div>
        </div>

        <!-- Hourly forecast section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8 overflow-hidden">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Hourly Forecast</h2>
                <div class="text-sm text-gray-500">Next 24 hours</div>
            </div>

            <div class="overflow-x-auto pb-2">
                <div class="flex space-x-4" style="min-width: max-content;">
                    @foreach($hourly_forecast as $index => $hour)
                        @if($index < 24) <!-- Limit to 24 hours -->
                            <div class="text-center p-3 min-w-[80px]">
                                <p class="text-gray-500 mb-2 text-sm">{{ $hour['time'] }}</p>
                                <div class="text-2xl mb-2">
                                    @if($hour['condition'] == 'Clear')
                                        <i class="ri-sun-line text-yellow-500"></i>
                                    @elseif($hour['condition'] == 'Clouds')
                                        <i class="ri-cloud-line text-gray-500"></i>
                                    @elseif($hour['condition'] == 'Rain')
                                        <i class="ri-rainy-line text-blue-500"></i>
                                    @elseif($hour['condition'] == 'Snow')
                                        <i class="ri-snowy-line text-blue-300"></i>
                                    @elseif($hour['condition'] == 'Thunderstorm')
                                        <i class="ri-thunderstorms-line text-purple-500"></i>
                                    @elseif($hour['condition'] == 'Mist' || $hour['condition'] == 'Fog')
                                        <i class="ri-mist-line text-gray-400"></i>
                                    @else
                                        <i class="ri-cloud-line text-gray-500"></i>
                                    @endif
                                </div>
                                <p class="font-semibold text-gray-800">
                                    <span x-show="unit === 'metric'">{{ $hour['temp'] }}°C</span>
                                    <span x-show="unit === 'imperial'" x-cloak>{{ round($hour['temp'] * 9/5 + 32) }}°F</span>
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <span x-show="unit === 'metric'">{{ $hour['wind_speed'] }} m/s</span>
                                    <span x-show="unit === 'imperial'" x-cloak>{{ round($hour['wind_speed'] * 2.237, 1) }} mph</span>
                                </p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 10-day forecast section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">10-Day Forecast</h2>
            </div>

            <div class="divide-y divide-gray-100">
                @foreach($daily_forecast as $index => $day)
                    @if($index < 10) <!-- Limit to 10 days -->
                        <div class="py-3 flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 text-left">
                                    <p class="font-medium">{{ $day['day'] }}</p>
                                    <p class="text-xs text-gray-500">{{ date('M j', strtotime($day['date'])) }}</p>
                                </div>

                                <div class="w-8 text-center">
                                    @if($day['condition'] == 'Clear')
                                        <i class="ri-sun-line text-yellow-500"></i>
                                    @elseif($day['condition'] == 'Clouds')
                                        <i class="ri-cloud-line text-gray-500"></i>
                                    @elseif($day['condition'] == 'Rain')
                                        <i class="ri-rainy-line text-blue-500"></i>
                                    @elseif($day['condition'] == 'Snow')
                                        <i class="ri-snowy-line text-blue-300"></i>
                                    @elseif($day['condition'] == 'Thunderstorm')
                                        <i class="ri-thunderstorms-line text-purple-500"></i>
                                    @elseif($day['condition'] == 'Mist' || $day['condition'] == 'Fog')
                                        <i class="ri-mist-line text-gray-400"></i>
                                    @else
                                        <i class="ri-cloud-line text-gray-500"></i>
                                    @endif
                                </div>

                                <div class="hidden md:block w-32 text-xs text-gray-500">
                                    {{ $day['description'] }}
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="flex items-center space-x-4">
                                    <div class="text-right w-20">
                                        <div class="flex items-center justify-end">
                                            <span class="text-xs text-blue-500 mr-1"><i class="ri-drop-line"></i></span>
                                            <span class="text-xs text-gray-500">{{ $day['precipitation_chance'] }}%</span>
                                        </div>
                                    </div>

                                    <div class="text-right w-16">
                                        <span x-show="unit === 'metric'" class="text-gray-400 text-sm">{{ $day['temp_min'] }}°</span>
                                        <span x-show="unit === 'imperial'" x-cloak class="text-gray-400 text-sm">{{ round($day['temp_min'] * 9/5 + 32) }}°</span>
                                        <span class="mx-1 text-gray-300">-</span>
                                        <span x-show="unit === 'metric'" class="text-gray-800 font-medium">{{ $day['temp_max'] }}°</span>
                                        <span x-show="unit === 'imperial'" x-cloak class="text-gray-800 font-medium">{{ round($day['temp_max'] * 9/5 + 32) }}°</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Weather alert suggestion -->
        <div class="bg-indigo-50 rounded-xl p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0 mr-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                        <i class="ri-notification-3-line text-indigo-600 text-xl"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Stay Informed About {{ $city }}'s Weather</h3>
                    <p class="text-gray-600 mb-4">Set up personalized weather alerts and receive notifications when specific conditions are met.</p>
                    <a href="{{ route('subscriptions.create', ['city' => $city]) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-500 font-medium">
                        Create Alert
                        <i class="ri-arrow-right-line ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
