@extends('layouts.app')

@section('title', 'Manage Subscriptions')

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Page header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Manage Your Weather Alerts</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">View and manage all your weather alert subscriptions in one place.</p>
        </div>

        <!-- Success message -->
        @if(session('success'))
            <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-8 flex items-start">
                <i class="ri-checkbox-circle-line text-xl mr-3 flex-shrink-0 mt-0.5"></i>
                <div>
                    <p class="font-medium">Success!</p>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Search form -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-10 mx-4 sm:mx-0">
            <div class="p-6 md:p-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Find Your Subscriptions</h2>
                <form action="{{ route('subscriptions.index') }}" method="GET" class="space-y-4 md:space-y-0 md:flex md:items-end md:gap-4">
                    <div class="flex-1">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div>
                            <input
                                type="email"
                                class="block w-full px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                id="email"
                                name="email"
                                value="{{ $email }}"
                                placeholder="Enter your email address"
                                required
                                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                title="Please enter a valid email address"
                            >
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Enter the email you used for your subscriptions</p>
                    </div>
                    <div class="md:w-auto">
                        <button type="submit" class="w-full md:w-auto bg-indigo-600 text-white hover:bg-indigo-700 transition-colors duration-200 font-medium py-2.5 px-6 rounded-lg shadow-sm flex items-center justify-center">
                            <i class="ri-search-line mr-2"></i>
                            View Subscriptions
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Empty state -->
        @if($email && $subscriptions->isEmpty())
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ri-inbox-line text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">No Subscriptions Found</h3>
                    <p class="text-gray-600 mb-6">We couldn't find any weather alert subscriptions for <strong>{{ $email }}</strong>.</p>
                    <a href="{{ route('subscriptions.create') }}" class="inline-flex items-center justify-center bg-indigo-600 text-white hover:bg-indigo-700 transition-colors duration-200 font-medium py-2.5 px-6 rounded-lg shadow-sm">
                        <i class="ri-add-line mr-2"></i>
                        Create New Subscription
                    </a>
                </div>
            </div>
        @endif

        <!-- Subscription list -->
        @if($subscriptions->isNotEmpty())
            <div class="bg-white rounded-xl shadow-md overflow-hidden mb-10 mx-4 sm:mx-0">
                <div class="bg-indigo-50 py-5 px-6 border-b border-indigo-100">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-800">Your Subscriptions</h2>
                        <span class="bg-indigo-100 text-indigo-700 text-sm py-1 px-3 rounded-full">{{ $subscriptions->count() }} {{ Str::plural('subscription', $subscriptions->count()) }}</span>
                    </div>
                </div>

                <div class="divide-y divide-gray-200">
                    @foreach($subscriptions as $subscription)
                        <div class="p-6 hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <!-- Subscription details -->
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <h3 class="text-lg font-semibold text-gray-800 mr-3">{{ $subscription->city->name }}</h3>
                                        <span class="
                                            @if($subscription->condition_type == 'temperature_above')
                                                bg-red-100 text-red-700
                                            @elseif($subscription->condition_type == 'temperature_below')
                                                bg-blue-100 text-blue-700
                                            @elseif($subscription->condition_type == 'rain')
                                                bg-blue-100 text-blue-700
                                            @elseif($subscription->condition_type == 'snow')
                                                bg-indigo-100 text-indigo-700
                                            @elseif($subscription->condition_type == 'wind_speed_above')
                                                bg-yellow-100 text-yellow-700
                                            @else
                                                bg-gray-100 text-gray-700
                                            @endif
                                            text-xs py-1 px-2 rounded-full
                                        ">
                                            {{ $conditionTypes[$subscription->condition_type] }}
                                        </span>
                                    </div>
                                    <div class="text-gray-600 space-y-1">
                                        @if(in_array($subscription->condition_type, ['temperature_below', 'temperature_above']))
                                            <p class="flex items-center">
                                                <i class="ri-temp-hot-line mr-2 text-gray-400"></i>
                                                <span>{{ $subscription->condition_value }}°C</span>
                                            </p>
                                        @elseif($subscription->condition_type == 'wind_speed_above')
                                            <p class="flex items-center">
                                                <i class="ri-windy-line mr-2 text-gray-400"></i>
                                                <span>{{ $subscription->condition_value }} m/s</span>
                                            </p>
                                        @endif
                                        <p class="flex items-center">
                                            <i class="ri-time-line mr-2 text-gray-400"></i>
                                            <span>Created {{ $subscription->created_at->format('M d, Y') }}</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Action buttons -->
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('weather.show', ['city' => $subscription->city->name]) }}" class="inline-flex items-center justify-center bg-white text-indigo-600 hover:bg-indigo-50 transition-colors duration-200 border border-indigo-200 font-medium py-2 px-4 rounded-lg">
                                        <i class="ri-cloud-line mr-1"></i>
                                        Check Weather
                                    </a>
                                    <form action="{{ route('subscriptions.destroy', $subscription->id) }}" method="POST" x-data="{ confirmDelete: false }" @submit.prevent="confirmDelete ? $el.submit() : confirmDelete = true">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center bg-white text-red-600 hover:bg-red-50 transition-colors duration-200 border border-red-200 font-medium py-2 px-4 rounded-lg" :class="{ 'bg-red-600 text-white hover:bg-red-700 border-red-600': confirmDelete }">
                                            <template x-if="!confirmDelete">
                                                <i class="ri-delete-bin-line mr-1"></i>
                                            </template>
                                            <span x-text="confirmDelete ? 'Confirm Delete' : 'Delete'"></span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Create new subscription button -->
            <div class="text-center">
                <a href="{{ route('subscriptions.create') }}" class="inline-flex items-center justify-center bg-indigo-600 text-white hover:bg-indigo-700 transition-colors duration-200 font-medium py-3 px-6 rounded-lg shadow-md">
                    <i class="ri-add-line mr-2"></i>
                    Create New Subscription
                </a>
            </div>
        @endif

        <!-- No email entered yet -->
        @if(!$email)
            <div class="bg-indigo-50 rounded-xl p-8 md:p-10 text-center mx-4 sm:mx-0">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ri-mail-open-line text-indigo-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Enter Your Email</h3>
                <p class="text-gray-600 mb-4">Enter your email address above to view and manage your weather alert subscriptions.</p>
                <p class="text-sm text-gray-500">Don't have any subscriptions yet?</p>
                <a href="{{ route('subscriptions.create') }}" class="inline-flex items-center justify-center mt-3 text-indigo-600 hover:text-indigo-500 font-medium">
                    Create your first subscription
                    <i class="ri-arrow-right-line ml-1"></i>
                </a>
            </div>
        @endif
    </div>
@endsection
