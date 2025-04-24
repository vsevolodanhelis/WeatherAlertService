@extends('layouts.app')

@section('title', 'Subscribe to Weather Alerts')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Page header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Subscribe to Weather Alerts</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Get notified when specific weather conditions are met in your city of interest.</p>
        </div>

        <div class="grid md:grid-cols-5 gap-8">
            <!-- Subscription form -->
            <div class="md:col-span-3">
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6 md:mb-0">
                    <div class="bg-white py-4 px-6 border-b border-gray-100">
                        <h2 class="text-xl font-semibold text-gray-800">Create New Alert</h2>
                    </div>

                    <div class="p-6">
                        @if($errors->any())
                            <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6">
                                <div class="flex items-center mb-2">
                                    <i class="ri-error-warning-line mr-2"></i>
                                    <span class="font-medium">Please fix the following errors:</span>
                                </div>
                                <ul class="list-disc pl-5 space-y-1 text-sm">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('subscriptions.store') }}" method="POST" x-data="{
                            conditionType: '{{ old('condition_type', '') }}',
                            needsValue() {
                                return ['temperature_below', 'temperature_above', 'wind_speed_above', 'high_uv_index', 'extreme_uv_index', 'high_humidity', 'low_humidity', 'pressure_increase', 'pressure_decrease'].includes(this.conditionType);
                            },
                            getValueLabel() {
                                if (this.conditionType.includes('temperature')) {
                                    return 'Temperature (°C)';
                                } else if (this.conditionType === 'wind_speed_above') {
                                    return 'Wind Speed (m/s)';
                                } else if (this.conditionType.includes('uv_index')) {
                                    return 'UV Index (1-12)';
                                } else if (this.conditionType.includes('humidity')) {
                                    return 'Humidity (%)';
                                } else if (this.conditionType.includes('pressure')) {
                                    return 'Pressure Change (hPa)';
                                }
                                return 'Value';
                            },
                            getValueMin() {
                                if (this.conditionType.includes('temperature')) {
                                    return -100;
                                } else if (this.conditionType === 'wind_speed_above') {
                                    return 0;
                                } else if (this.conditionType.includes('uv_index')) {
                                    return 1;
                                } else if (this.conditionType.includes('humidity')) {
                                    return 0;
                                } else if (this.conditionType.includes('pressure')) {
                                    return -50;
                                }
                                return -100;
                            },
                            getValueMax() {
                                if (this.conditionType.includes('temperature')) {
                                    return 100;
                                } else if (this.conditionType === 'wind_speed_above') {
                                    return 200;
                                } else if (this.conditionType.includes('uv_index')) {
                                    return 12;
                                } else if (this.conditionType.includes('humidity')) {
                                    return 100;
                                } else if (this.conditionType.includes('pressure')) {
                                    return 50;
                                }
                                return 100;
                            }
                        }">
                            @csrf
                            <!-- Email field -->
                            <div class="mb-6">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <div>
                                    <input
                                        type="email"
                                        class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror"
                                        id="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        placeholder="your@email.com"
                                        required
                                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                        title="Please enter a valid email address"
                                    >
                                </div>
                                @error('email')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- City field -->
                            <div class="mb-6">
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <div>
                                    <input
                                        type="text"
                                        class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('city') border-red-500 @enderror"
                                        id="city"
                                        name="city"
                                        value="{{ old('city', request('city')) }}"
                                        placeholder="Enter city name"
                                        required
                                        pattern="^[a-zA-Z\s\-\.]+$"
                                        title="City name can only contain letters, spaces, hyphens, and periods"
                                    >
                                </div>
                                @error('city')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Condition type field -->
                            <div class="mb-6">
                                <label for="condition_type" class="block text-sm font-medium text-gray-700 mb-1">Condition Type</label>
                                <div>
                                    <select
                                        class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white @error('condition_type') border-red-500 @enderror"
                                        id="condition_type"
                                        name="condition_type"
                                        required
                                        x-model="conditionType"
                                    >
                                        <option value="">Select a condition</option>
                                        @foreach($conditionTypes as $value => $label)
                                            <option value="{{ $value }}" {{ old('condition_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('condition_type')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Condition value field -->
                            <div class="mb-6" x-show="needsValue()" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                                <label for="condition_value" class="block text-sm font-medium text-gray-700 mb-1" x-text="getValueLabel()">Condition Value</label>
                                <div>
                                    <input
                                        type="number"
                                        step="0.01"
                                        class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('condition_value') border-red-500 @enderror"
                                        id="condition_value"
                                        name="condition_value"
                                        value="{{ old('condition_value') }}"
                                        placeholder="Enter value"
                                        :min="getValueMin()"
                                        :max="getValueMax()"
                                        :required="needsValue()"
                                    >
                                </div>
                                @error('condition_value')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                                <div class="text-sm text-gray-500 mt-1">
                                    <span x-show="conditionType.includes('temperature')">Enter a temperature value between -100°C and 100°C.</span>
                                    <span x-show="conditionType === 'wind_speed_above'">Enter a wind speed value between 0 and 200 m/s.</span>
                                    <span x-show="conditionType.includes('uv_index')">Enter a UV index value between 1 and 12.</span>
                                    <span x-show="conditionType.includes('humidity')">Enter a humidity percentage between 0% and 100%.</span>
                                    <span x-show="conditionType.includes('pressure')">Enter a pressure change value between -50 hPa and 50 hPa.</span>
                                </div>
                            </div>

                            <!-- Submit button -->
                            <button type="submit" class="w-full bg-indigo-600 text-white hover:bg-indigo-700 transition-colors duration-200 font-semibold py-3 px-6 rounded-lg shadow-md flex items-center justify-center">
                                <span class="flex items-center justify-center w-5 h-5 mr-2">
                                    <i class="ri-notification-3-line"></i>
                                </span>
                                Create Alert Subscription
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Information sidebar -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 py-5 px-6">
                        <h2 class="text-lg font-semibold text-white">Weather Alert Information</h2>
                    </div>

                    <!-- How Alerts Work Section -->
                    <div class="p-6 md:p-8 border-b border-gray-100">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">How Alerts Work</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-3">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <span class="text-indigo-600 font-medium">1</span>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-base font-medium text-gray-800 mb-1">Create a Subscription</h3>
                                    <p class="text-sm text-gray-600">Fill out the form with your email, city, and the weather condition you want to monitor.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-3">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <span class="text-indigo-600 font-medium">2</span>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-base font-medium text-gray-800 mb-1">Automated Monitoring</h3>
                                    <p class="text-sm text-gray-600">Our system checks the weather conditions for your city daily.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-3">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <span class="text-indigo-600 font-medium">3</span>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-base font-medium text-gray-800 mb-1">Receive Notifications</h3>
                                    <p class="text-sm text-gray-600">When your specified condition is met, you'll receive an email notification.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Available Alert Types Section -->
                    <div class="p-6 md:p-8 bg-gray-50">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Available Alert Types</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <h4 class="font-medium text-indigo-700 mb-2 text-sm uppercase tracking-wide">Weather Conditions</h4>
                                <ul class="space-y-3 ml-2">
                                    <li class="flex items-center text-gray-800">
                                        <i class="ri-temp-hot-line mr-3 text-indigo-600"></i>
                                        <span>Temperature above threshold</span>
                                    </li>
                                    <li class="flex items-center text-gray-800">
                                        <i class="ri-temp-cold-line mr-3 text-indigo-600"></i>
                                        <span>Temperature below threshold</span>
                                    </li>
                                    <li class="flex items-center text-gray-800">
                                        <i class="ri-rainy-line mr-3 text-indigo-600"></i>
                                        <span>Rain detection</span>
                                    </li>
                                    <li class="flex items-center text-gray-800">
                                        <i class="ri-snowy-line mr-3 text-indigo-600"></i>
                                        <span>Snow detection</span>
                                    </li>
                                    <li class="flex items-center text-gray-800">
                                        <i class="ri-windy-line mr-3 text-indigo-600"></i>
                                        <span>Wind speed above threshold</span>
                                    </li>
                                </ul>
                            </div>

                            <div>
                                <h4 class="font-medium text-indigo-700 mb-2 text-sm uppercase tracking-wide">Special Alerts & Environment</h4>
                                <ul class="space-y-3 ml-2">
                                    <li class="flex items-center text-gray-800">
                                        <i class="ri-thunderstorms-line mr-3 text-indigo-600"></i>
                                        <span>Thunderstorm alerts</span>
                                    </li>
                                    <li class="flex items-center text-gray-800">
                                        <i class="ri-tornado-line mr-3 text-indigo-600"></i>
                                        <span>Tornado warnings</span>
                                    </li>
                                    <li class="flex items-center text-gray-800">
                                        <i class="ri-haze-line mr-3 text-indigo-600"></i>
                                        <span>Air quality alerts</span>
                                    </li>
                                    <li class="flex items-center text-gray-800">
                                        <i class="ri-sun-line mr-3 text-indigo-600"></i>
                                        <span>UV index alerts</span>
                                    </li>
                                    <li class="flex items-center text-gray-800">
                                        <i class="ri-water-percent-line mr-3 text-indigo-600"></i>
                                        <span>Humidity alerts</span>
                                    </li>
                                    <li class="flex items-center text-gray-800">
                                        <i class="ri-speed-up-line mr-3 text-indigo-600"></i>
                                        <span>Pressure change alerts</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

