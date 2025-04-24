@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <script>
        function showCategory(category) {
            // Hide all category content
            document.querySelectorAll('.category-content').forEach(function(el) {
                el.classList.add('hidden');
            });

            // Show the selected category
            document.getElementById(category + '-category').classList.remove('hidden');

            // Update button styles
            if (category === 'weather') {
                document.querySelector('button[onclick="showCategory(\'weather\')"]').classList.add('bg-indigo-600');
                document.querySelector('button[onclick="showCategory(\'weather\')"]').classList.remove('bg-gray-700');

                document.querySelector('button[onclick="showCategory(\'health\')"]').classList.remove('bg-indigo-600');
                document.querySelector('button[onclick="showCategory(\'health\')"]').classList.add('bg-gray-700');
            } else {
                document.querySelector('button[onclick="showCategory(\'health\')"]').classList.add('bg-indigo-600');
                document.querySelector('button[onclick="showCategory(\'health\')"]').classList.remove('bg-gray-700');

                document.querySelector('button[onclick="showCategory(\'weather\')"]').classList.remove('bg-indigo-600');
                document.querySelector('button[onclick="showCategory(\'weather\')"]').classList.add('bg-gray-700');
            }
        }
    </script>
    <!-- Hero section with gradient background -->
    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-xl overflow-hidden mb-12">
        <div class="max-w-4xl mx-auto px-6 py-16 text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 animate-fade-in">Weather Alert Service</h1>
            <p class="text-xl opacity-75 mb-8 animate-fade-in">Stay informed about weather conditions that matter to you.</p>
            <h3 class="text-lg font-medium mb-4">Explore Your Options</h3>
            <div class="flex flex-row justify-center gap-4 animate-slide-in max-w-md mx-auto">
                <a href="{{ route('weather.index') }}" class="bg-indigo-700 hover:bg-indigo-600 transition-colors duration-200 font-medium py-3 px-6 rounded-lg shadow-md flex-1 flex items-center justify-center">
                    <span>Check Weather</span>
                </a>
                <a href="{{ route('subscriptions.create') }}" class="bg-indigo-500 hover:bg-indigo-400 transition-colors duration-200 font-medium py-3 px-6 rounded-lg shadow-md flex-1 flex items-center justify-center">
                    <span>Subscribe to Alerts</span>
                </a>
            </div>
        </div>
    </div>

    <!-- How it works section with cards -->
    <div class="mb-16 px-4 sm:px-6 mt-24 py-12">
        <h2 class="text-2xl font-semibold text-center mb-8 text-gray-800">How It Works</h2>
        <div class="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto text-center">
            <div class="bg-white p-6 rounded-xl space-y-3 flex flex-col h-full shadow-md">
                <div class="text-indigo-600 text-3xl mb-1">1</div>
                <h3 class="font-semibold text-lg text-gray-800">Check Weather</h3>
                <p class="text-gray-600 text-sm">Get current weather information for any city around the world with detailed forecasts and conditions.</p>
            </div>

            <div class="bg-white p-6 rounded-xl space-y-3 flex flex-col h-full shadow-md">
                <div class="text-indigo-600 text-3xl mb-1">2</div>
                <h3 class="font-semibold text-lg text-gray-800">Subscribe to Alerts</h3>
                <p class="text-gray-600 text-sm">Set up personalized weather alerts based on specific conditions that matter to you and your activities.</p>
            </div>

            <div class="bg-white p-6 rounded-xl space-y-3 flex flex-col h-full shadow-md">
                <div class="text-indigo-600 text-3xl mb-1">3</div>
                <h3 class="font-semibold text-lg text-gray-800">Receive Notifications</h3>
                <p class="text-gray-600 text-sm">Get timely email notifications when your specified weather conditions are met, keeping you informed.</p>
            </div>
        </div>
    </div>

    <!-- Alert types section with categories -->
    <div class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-gray-900 text-white px-6 py-12 space-y-16 mx-4 sm:mx-6 rounded-xl">
        <h2 class="text-2xl font-semibold text-center mb-6">Available Alert Types</h2>

        <!-- Category tabs -->
        <div class="flex justify-center gap-4 mb-6 mt-8">
            <button type="button" class="bg-indigo-600 px-4 py-2 rounded-full text-sm font-medium" onclick="showCategory('weather')">
                Weather Phenomena
            </button>
            <button type="button" class="bg-gray-700 px-4 py-2 rounded-full text-sm font-medium" onclick="showCategory('health')">
                Health & Environment
            </button>
        </div>

        <!-- Weather Phenomena category -->
        <div id="weather-category" class="category-content max-w-6xl mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                <!-- Temperature Alerts -->
                <div class="bg-white p-5 rounded-xl space-y-2 h-full shadow-md">
                    <div class="text-indigo-600 text-xl text-center">🌡️</div>
                    <h3 class="font-semibold text-gray-800 text-center">Temperature Alerts</h3>
                    <p class="text-sm text-gray-600 text-center">Get notified when temperature rises above or falls below a specific threshold.</p>
                    <ul class="text-sm text-indigo-500 mt-2 text-center">
                        <li>— Above threshold —</li>
                        <li>— Below threshold —</li>
                    </ul>
                </div>

                <!-- Precipitation Alerts -->
                <div class="bg-white p-5 rounded-xl space-y-2 h-full shadow-md">
                    <div class="text-indigo-600 text-xl text-center">🌧️</div>
                    <h3 class="font-semibold text-gray-800 text-center">Precipitation Alerts</h3>
                    <p class="text-sm text-gray-600 text-center">Receive alerts when it starts raining or snowing in your selected location.</p>
                    <ul class="text-sm text-indigo-500 mt-2 text-center">
                        <li>— Rain alerts —</li>
                        <li>— Snow alerts —</li>
                    </ul>
                </div>

                <!-- Wind Alerts -->
                <div class="bg-white p-5 rounded-xl space-y-2 h-full shadow-md">
                    <div class="text-indigo-600 text-xl text-center">🌬️</div>
                    <h3 class="font-semibold text-gray-800 text-center">Wind Alerts</h3>
                    <p class="text-sm text-gray-600 text-center">Stay informed about high wind speeds that may affect your area.</p>
                    <ul class="text-sm text-indigo-500 mt-2 text-center">
                        <li>— Wind speed above threshold —</li>
                    </ul>
                </div>

                <!-- Extreme Weather Alerts -->
                <div class="bg-white p-5 rounded-xl space-y-2 h-full shadow-md">
                    <div class="text-indigo-600 text-xl text-center">⚡</div>
                    <h3 class="font-semibold text-gray-800 text-center">Extreme Weather Alerts</h3>
                    <p class="text-sm text-gray-600 text-center">Get notified about severe weather events like thunderstorms, hurricanes, and tornadoes.</p>
                    <ul class="text-sm text-indigo-500 mt-2 text-center">
                        <li>— Thunderstorm warning —</li>
                        <li>— Tornado warning —</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Health & Environment category -->
        <div id="health-category" class="category-content max-w-6xl mx-auto hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <!-- Air Quality Alerts -->
                <div class="bg-white p-5 rounded-xl space-y-2 h-full shadow-md">
                    <div class="text-indigo-600 text-xl text-center">🌫️</div>
                    <h3 class="font-semibold text-gray-800 text-center">Air Quality Alerts</h3>
                    <p class="text-sm text-gray-600 text-center">Stay informed about air quality conditions and pollution levels in your area.</p>
                    <ul class="text-sm text-indigo-500 mt-2 text-center">
                        <li>— Poor air quality warning —</li>
                        <li>— Pollution level above threshold —</li>
                    </ul>
                </div>

                <!-- UV Index Alerts -->
                <div class="bg-white p-5 rounded-xl space-y-2 h-full shadow-md">
                    <div class="text-indigo-600 text-xl text-center">☀️</div>
                    <h3 class="font-semibold text-gray-800 text-center">UV Index Alerts</h3>
                    <p class="text-sm text-gray-600 text-center">Get notified when UV radiation levels are high enough to cause potential skin damage.</p>
                    <ul class="text-sm text-indigo-500 mt-2 text-center">
                        <li>— High UV index warning —</li>
                        <li>— Extreme UV index warning —</li>
                    </ul>
                </div>

                <!-- Humidity Alerts -->
                <div class="bg-white p-5 rounded-xl space-y-2 h-full shadow-md">
                    <div class="text-indigo-600 text-xl text-center">💦</div>
                    <h3 class="font-semibold text-gray-800 text-center">Humidity Alerts</h3>
                    <p class="text-sm text-gray-600 text-center">Stay informed about humidity levels that can affect comfort and health.</p>
                    <ul class="text-sm text-indigo-500 mt-2 text-center">
                        <li>— High humidity alert —</li>
                        <li>— Low humidity alert —</li>
                    </ul>
                </div>

                <!-- Pressure Change Alerts -->
                <div class="bg-white p-5 rounded-xl space-y-2 h-full shadow-md">
                    <div class="text-indigo-600 text-xl text-center">🌡️</div>
                    <h3 class="font-semibold text-gray-800 text-center">Pressure Alerts</h3>
                    <p class="text-sm text-gray-600 text-center">Get notified about significant changes in barometric pressure that may affect weather or health.</p>
                    <ul class="text-sm text-indigo-500 mt-2 text-center">
                        <li>— Rapid pressure increase —</li>
                        <li>— Rapid pressure decrease —</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Spacer -->
        <div class="mt-12"></div>
    </div>
@endsection
