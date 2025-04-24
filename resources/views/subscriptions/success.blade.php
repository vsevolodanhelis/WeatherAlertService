@extends('layouts.app')

@section('title', 'Subscription Successful')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Success header with confetti animation -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-8 text-white text-center relative overflow-hidden">
                <!-- Animated confetti (purely CSS) -->
                <div class="absolute inset-0 overflow-hidden opacity-30" aria-hidden="true">
                    @for ($i = 1; $i <= 20; $i++)
                        <div class="absolute animate-confetti-{{ $i % 5 + 1 }}"
                             style="
                                left: {{ rand(0, 100) }}%;
                                top: -20px;
                                width: {{ rand(5, 12) }}px;
                                height: {{ rand(5, 12) }}px;
                                background-color: {{ ['#ffffff', '#ffcc00', '#ff66cc', '#66ccff', '#99ff99'][rand(0, 4)] }};
                                transform: rotate({{ rand(0, 360) }}deg);
                                animation-delay: {{ $i * 0.1 }}s;
                             ">
                        </div>
                    @endfor
                </div>

                <div class="relative z-10">
                    <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="ri-checkbox-circle-line text-white text-5xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold mb-2">Subscription Successful!</h1>
                    <p class="text-xl opacity-90">Your weather alert has been created.</p>
                </div>
            </div>

            <!-- Success message and details -->
            <div class="p-8 text-center">
                <div class="max-w-md mx-auto">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Thank you for subscribing!</h2>
                    <p class="text-gray-600 mb-6">You will now receive weather alerts when your specified conditions are met. We'll check the weather conditions daily and send you an email notification.</p>

                    <!-- Subscription details card -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-8 text-left">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Subscription Details</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="ri-mail-line text-gray-400 mr-3 w-5"></i>
                                <span class="text-gray-700">{{ session('subscription_email') }}</span>
                            </div>
                            @if(session('subscription_city'))
                            <div class="flex items-center">
                                <i class="ri-map-pin-line text-gray-400 mr-3 w-5"></i>
                                <span class="text-gray-700">{{ session('subscription_city') }}</span>
                            </div>
                            @endif
                            @if(session('subscription_condition'))
                            <div class="flex items-center">
                                <i class="ri-cloud-line text-gray-400 mr-3 w-5"></i>
                                <span class="text-gray-700">{{ session('subscription_condition') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('subscriptions.create') }}" class="bg-indigo-600 text-white hover:bg-indigo-700 transition-colors duration-200 font-semibold py-3 px-6 rounded-lg shadow-md flex items-center justify-center">
                            <i class="ri-add-line mr-2"></i>
                            Create Another Alert
                        </a>
                        <a href="{{ route('subscriptions.index', ['email' => session('subscription_email')]) }}" class="bg-white text-indigo-600 hover:bg-gray-50 transition-colors duration-200 border border-indigo-200 font-semibold py-3 px-6 rounded-lg shadow-sm flex items-center justify-center">
                            <i class="ri-settings-line mr-2"></i>
                            Manage Subscriptions
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to home link -->
        <div class="text-center mt-8">
            <a href="{{ route('home') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-500 font-medium">
                <i class="ri-arrow-left-line mr-2"></i>
                Back to Home
            </a>
        </div>
    </div>

    <style>
        /* Confetti animations */
        @keyframes confetti-1 {
            0% { transform: translateY(0) rotate(0deg); }
            100% { transform: translateY(100vh) rotate(360deg); }
        }
        @keyframes confetti-2 {
            0% { transform: translateY(0) rotate(0deg); }
            100% { transform: translateY(100vh) rotate(-180deg); }
        }
        @keyframes confetti-3 {
            0% { transform: translateY(0) rotate(0deg); }
            100% { transform: translateY(100vh) rotate(180deg); }
        }
        @keyframes confetti-4 {
            0% { transform: translateY(0) rotate(0deg); }
            100% { transform: translateY(100vh) rotate(-90deg); }
        }
        @keyframes confetti-5 {
            0% { transform: translateY(0) rotate(0deg); }
            100% { transform: translateY(100vh) rotate(90deg); }
        }

        .animate-confetti-1 { animation: confetti-1 4s linear forwards; }
        .animate-confetti-2 { animation: confetti-2 5s linear forwards; }
        .animate-confetti-3 { animation: confetti-3 4.5s linear forwards; }
        .animate-confetti-4 { animation: confetti-4 6s linear forwards; }
        .animate-confetti-5 { animation: confetti-5 5.5s linear forwards; }
    </style>
@endsection
