@extends('layouts.app')

@section('title', 'Check Weather')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Enhanced page header with gradient background -->
        <div class="text-center mb-10 bg-gradient-to-r from-indigo-100 to-blue-100 rounded-xl py-8 px-4 shadow-sm">
            <div class="relative">
                <!-- Decorative weather icons -->
                <div class="absolute left-10 top-0 opacity-20 hidden md:block">
                    <i class="ri-sun-line text-yellow-500 text-4xl"></i>
                </div>
                <div class="absolute right-10 top-0 opacity-20 hidden md:block">
                    <i class="ri-cloud-line text-blue-500 text-4xl"></i>
                </div>

                <h1 class="text-3xl md:text-4xl font-bold text-indigo-900 mb-4">Check Current Weather</h1>
                <p class="text-lg text-indigo-700 max-w-2xl mx-auto">Enter a city name to get detailed weather forecasts, hourly predictions, and more.</p>
            </div>
        </div>

        <!-- Enhanced weather search form with purple gradient background -->
        <div class="rounded-xl shadow-md overflow-hidden mb-12 location-search" style="background-image: linear-gradient(to bottom right, #6366f1, #9333ea); color: white !important;">
            <!-- No decorative elements -->

            <div class="relative px-6 py-10 md:p-10">
                <form action="{{ route('weather.show') }}" method="GET" class="relative z-10 max-w-md mx-auto">
                    <div class="text-center mb-8">
                        <i class="ri-map-pin-line text-3xl mb-3" style="color: white !important;"></i>
                        <h2 class="text-2xl font-bold" style="color: white !important;">Find Your Location</h2>
                        <p class="text-sm mt-2" style="color: white !important;">Get instant access to current conditions and forecasts</p>
                    </div>

                    <div class="mb-6">
                        <div class="flex items-center justify-center">
                            <div style="width: 300px;" class="mr-3">
                                <input
                                    type="text"
                                    class="block w-full px-4 py-3 border-0 rounded-lg focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-600 focus:outline-none shadow-sm text-gray-900"
                                    id="city"
                                    name="city"
                                    value="{{ old('city') }}"
                                    placeholder="Enter city name (e.g., London)"
                                    required
                                    pattern="^[a-zA-Z\s\-\.]+$"
                                    title="City name can only contain letters, spaces, hyphens, and periods"
                                    minlength="2"
                                    maxlength="100"
                                    autocomplete="off"
                                >
                            </div>
                            <button type="submit" class="flex-shrink-0 flex items-center justify-center w-10 h-10 bg-indigo-600 rounded-full shadow-md text-white hover:bg-indigo-700 transition-all duration-200">
                                <i class="ri-arrow-right-line text-xl"></i>
                            </button>
                        </div>
                        @error('city')
                            <div class="text-white text-sm mt-2 bg-red-500 bg-opacity-25 rounded px-3 py-1">
                                <i class="ri-error-warning-line mr-1"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </form>
            </div>
        </div>

        <!-- Enhanced popular cities section -->
        <div class="mt-32 mb-24 px-4 sm:px-6 md:px-10 max-w-6xl mx-auto">
            <div class="flex items-center justify-center mb-20">
                <i class="ri-global-line text-indigo-600 text-2xl mr-2"></i>
                <h2 class="text-2xl font-bold text-gray-800">Popular Cities</h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-5 md:gap-8">
                @php
                    $popularCities = [
                        ['name' => 'London', 'icon' => 'ri-cloud-line', 'color' => 'text-black'],
                        ['name' => 'New York', 'icon' => 'ri-cloudy-line', 'color' => 'text-black'],
                        ['name' => 'Tokyo', 'icon' => 'ri-sun-line', 'color' => 'text-yellow-500'],
                        ['name' => 'Paris', 'icon' => 'ri-rainy-line', 'color' => 'text-blue-400'],
                        ['name' => 'Sydney', 'icon' => 'ri-sun-line', 'color' => 'text-orange-500'],
                        ['name' => 'Dubai', 'icon' => 'ri-sun-foggy-line', 'color' => 'text-yellow-600'],
                        ['name' => 'Rome', 'icon' => 'ri-sun-cloudy-line', 'color' => 'text-indigo-400'],
                        ['name' => 'Singapore', 'icon' => 'ri-cloudy-line', 'color' => 'text-blue-400']
                    ];
                @endphp

                @foreach($popularCities as $city)
                    <a href="{{ route('weather.show', ['city' => $city['name']]) }}" class="bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-200 p-6 md:p-7 text-center transform hover:-translate-y-1">
                        <div class="{{ $city['color'] }} mb-4">
                            <i class="{{ $city['icon'] }} text-4xl"></i>
                        </div>
                        <span class="font-medium text-gray-800 text-lg">{{ $city['name'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Enhanced weather information section with cards -->
        <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl p-8 md:p-10 mx-4 sm:mx-0 shadow-md mt-32">
            <div class="text-center mb-20 mt-12">
                <div class="inline-flex items-center justify-center">
                    <h2 class="text-3xl font-bold text-indigo-800">Weather Service Features</h2>
                    <i class="ri-information-line text-indigo-600 text-2xl ml-3"></i>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <div class="bg-white rounded-xl shadow-md p-6 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-full mb-3 border-2 border-yellow-200">
                            <i class="ri-time-line text-yellow-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">Real-Time Data</h3>
                    </div>
                    <p class="text-gray-600 text-center">Our weather data is updated regularly to provide you with the most current conditions.</p>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-full mb-3 border-2 border-yellow-200">
                            <i class="ri-notification-3-line text-yellow-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">Weather Alerts</h3>
                    </div>
                    <p class="text-gray-600 text-center">Subscribe to receive notifications when specific weather conditions are met in your city.</p>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1">
                    <div class="text-center mb-4">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-full mb-3 border-2 border-yellow-200">
                            <i class="ri-calendar-line text-yellow-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">10-Day Forecast</h3>
                    </div>
                    <p class="text-gray-600 text-center">Plan ahead with our detailed 10-day weather forecasts for any location worldwide.</p>
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('subscriptions.create') }}" class="inline-flex items-center bg-indigo-600 text-white font-medium px-6 py-3 rounded-lg shadow-md hover:bg-indigo-700 transition-all duration-200">
                    <i class="ri-notification-3-line mr-2"></i>
                    Create Weather Alert
                    <i class="ri-arrow-right-line ml-2"></i>
                </a>
            </div>
        </div>
    </div>
@endsection
