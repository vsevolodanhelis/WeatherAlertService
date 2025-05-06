<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Alert Service - @yield('title', 'Home')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Remix Icons -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.0/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }

        /* Custom animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes slideInFromBottom {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .animate-slide-in {
            animation: slideInFromBottom 0.5s ease-out;
        }

        /* Weather icons styling */
        .weather-icon {
            font-size: 4rem;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        /* Enhanced color schemes for both modes */
        /* Light mode - pure black text on gradient background */
        body {
            background: linear-gradient(135deg, #f0f4ff, #e0e7ff, #dbeafe); /* Soft blue gradient background */
            color: #000000; /* Pure black for text */
            background-attachment: fixed; /* Keep the gradient fixed when scrolling */
        }

        /* Custom styles can be added here */

        /* Weather header specific styles */
        .weather-header *, .location-search * {
            color: white !important;
        }

        .bg-white {
            background-color: #ffffff; /* Pure white */
        }

        .text-gray-800 {
            color: #000000; /* Pure black */
        }

        .text-gray-700 {
            color: #111827; /* Near black */
        }

        .text-gray-600 {
            color: #1f2937; /* Very dark gray */
        }

        .text-gray-500 {
            color: #374151; /* Dark gray */
        }

        .border-gray-200 {
            border-color: #e5e7eb; /* Light gray border */
        }

        /* Weather Alert Service panel - keep white text */
        .bg-gradient-to-br.from-indigo-500.to-purple-600 .text-white,
        .bg-indigo-700 .text-white,
        .bg-gradient-to-r.from-indigo-600.to-indigo-500 .text-white {
            color: #ffffff !important; /* Force white text for these specific panels */
        }

        /* Alert Types - make titles black in light mode, white in dark mode */
        .bg-gray-800 h3, .bg-gray-900 h3 {
            color: #000000 !important; /* Black titles in light mode */
            text-align: center !important; /* Center the titles */
        }

        .dark .bg-gray-800 h3, .dark .bg-gray-900 h3 {
            color: #ffffff !important; /* White titles in dark mode */
        }

        /* Alert Types - make all text white in dark mode */
        .dark .bg-gray-800 p, .dark .bg-gray-900 p,
        .dark .bg-gray-800 li, .dark .bg-gray-900 li,
        .dark .bg-gray-800 .text-gray-400, .dark .bg-gray-900 .text-gray-400,
        .dark .bg-gray-800 .text-indigo-400, .dark .bg-gray-900 .text-indigo-400 {
            color: #ffffff !important; /* White text in dark mode */
        }

        /* Keep white text for specific elements */
        .bg-gradient-to-br.from-indigo-500.to-purple-600 .text-gray-100,
        .bg-indigo-700 .text-gray-100 {
            color: #f1f5f9 !important; /* Force light gray text for these specific panels */
        }

        /* Override text colors for light mode (except for specific panels) */
        .text-white:not(.bg-gradient-to-br.from-indigo-500.to-purple-600 *, .bg-indigo-700 *, .bg-gray-800 *, .bg-gray-900 *, [style*="background-image: linear-gradient"] *) {
            color: #000000; /* Pure black instead of white */
        }

        .text-gray-100:not(.bg-gradient-to-br.from-indigo-500.to-purple-600 *, .bg-indigo-700 *, .bg-gray-800 *, .bg-gray-900 *) {
            color: #111827; /* Near black instead of near-white */
        }

        .text-gray-200:not(.bg-gradient-to-br.from-indigo-500.to-purple-600 *, .bg-indigo-700 *, .bg-gray-800 *, .bg-gray-900 *) {
            color: #1f2937; /* Dark gray instead of very light gray */
        }

        /* Ensure white text on buttons with colored backgrounds */
        .bg-indigo-600, .bg-indigo-700, .bg-indigo-500,
        .bg-gradient-to-r.from-yellow-400.to-orange-500 {
            color: #ffffff !important;
        }

        /* Shadow adjustments for light mode */
        .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(15, 23, 42, 0.1);
        }

        .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.1);
        }

        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.1);
        }

        /* Dark mode - pure white text on dark backgrounds */
        .dark body {
            background-color: #111827; /* Very dark gray (near black) */
            color: #ffffff; /* Pure white */
        }

        .dark .bg-white {
            background-color: #1f2937; /* Dark gray */
        }

        .dark .text-gray-800 {
            color: #ffffff; /* Pure white */
        }

        .dark .text-gray-700 {
            color: #f9fafb; /* Near white */
        }

        .dark .text-gray-600 {
            color: #f3f4f6; /* Very light gray */
        }

        .dark .text-gray-500 {
            color: #e5e7eb; /* Light gray */
        }

        /* Override white text in dark mode */
        .dark .text-white {
            color: #ffffff; /* Pure white */
        }

        .dark .border-gray-200 {
            border-color: #374151; /* Dark gray border */
        }

        .dark .bg-gray-50 {
            background-color: #1f2937; /* Dark gray */
        }

        .dark .bg-gray-100 {
            background-color: #374151; /* Medium-dark gray */
        }

        .dark .hover\:bg-gray-50:hover {
            background-color: #374151; /* Better hover state */
        }

        .dark .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.5);
        }

        .dark .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5);
        }

        .dark .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
        }

        /* Accent color optimizations - more harmonious */
        .text-indigo-600 {
            color: #4338ca; /* Deep indigo */
        }

        .dark .text-indigo-400 {
            color: #818cf8; /* Bright indigo for dark mode */
        }

        .bg-indigo-600 {
            background-color: #4338ca; /* Deep indigo */
        }

        /* Button text colors */
        .bg-indigo-600, .bg-indigo-700, .bg-indigo-500 {
            color: #ffffff; /* White text for buttons with colored backgrounds */
        }

        .dark .bg-indigo-700 {
            background-color: #4338ca; /* Deeper indigo for dark mode */
        }

        .dark .bg-indigo-900 {
            background-color: #312e81; /* Very deep indigo for dark mode */
        }

        /* Button color overrides based on context */
        .dark button, .dark .btn, .dark [type='button'], .dark [type='submit'] {
            color: #ffffff; /* Pure white for buttons in dark mode */
        }

        /* Ensure white text on gradient buttons */
        .bg-gradient-to-r.from-yellow-400.to-orange-500,
        .bg-gradient-to-r.from-indigo-600.to-indigo-500 {
            color: #ffffff !important;
        }

        /* Weather Alert Service panel colors - keep consistent */
        .bg-gradient-to-br.from-indigo-500.to-purple-600 {
            background-image: linear-gradient(to bottom right, #6366f1, #9333ea);
        }

        .bg-indigo-700 {
            background-color: #4338ca;
        }

        /* Theme toggle animation */
        .theme-toggle-icon {
            transition: transform 0.5s ease;
        }

        .theme-toggle-icon.rotate {
            transform: rotate(360deg);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col transition-colors duration-300">
    <!-- Header with animated dropdown for mobile -->
    <header class="bg-white shadow-sm sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <i class="ri-cloud-line text-indigo-600 text-2xl mr-2"></i>
                        <span class="font-bold text-xl text-indigo-600">Weather Alert</span>
                    </a>
                </div>

                <!-- Desktop navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'text-indigo-600 font-medium' : 'text-gray-600 hover:text-indigo-500' }} transition-colors duration-200 py-2 text-sm font-medium">Home</a>
                    <a href="{{ route('weather.index') }}" class="{{ request()->routeIs('weather.*') ? 'text-indigo-600 font-medium' : 'text-gray-600 hover:text-indigo-500' }} transition-colors duration-200 py-2 text-sm font-medium">Weather</a>
                    <a href="{{ route('subscriptions.create') }}" class="{{ request()->routeIs('subscriptions.create') ? 'text-indigo-600 font-medium' : 'text-gray-600 hover:text-indigo-500' }} transition-colors duration-200 py-2 text-sm font-medium">Subscribe</a>
                    <a href="{{ route('subscriptions.index') }}" class="{{ request()->routeIs('subscriptions.index') ? 'text-indigo-600 font-medium' : 'text-gray-600 hover:text-indigo-500' }} transition-colors duration-200 py-2 text-sm font-medium">Manage Alerts</a>


                </nav>

                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">

                    <!-- Mobile menu button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-indigo-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <i class="ri-menu-line text-2xl" x-show="!mobileMenuOpen"></i>
                        <i class="ri-close-line text-2xl" x-show="mobileMenuOpen" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden" id="mobile-menu" x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 border-t border-gray-200">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-500' }} block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">Home</a>
                <a href="{{ route('weather.index') }}" class="{{ request()->routeIs('weather.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-500' }} block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">Weather</a>
                <a href="{{ route('subscriptions.create') }}" class="{{ request()->routeIs('subscriptions.create') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-500' }} block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">Subscribe</a>
                <a href="{{ route('subscriptions.index') }}" class="{{ request()->routeIs('subscriptions.index') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-500' }} block px-3 py-2 rounded-md text-base font-medium transition-colors duration-200">Manage Alerts</a>
            </div>
        </div>
    </header>

    <!-- Main content -->
    <main class="flex-grow py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-gray-800 to-indigo-900 border-t border-indigo-400 mt-12 text-white shadow-md">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <i class="ri-cloud-line text-white text-xl mr-2"></i>
                    <span class="text-white font-medium">Weather Alert Service</span>
                </div>
                <div class="text-sm text-white">
                    &copy; {{ date('Y') }} Weather Alert Service. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <!-- Enhanced toast notifications -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-50" x-data="{ show: false, message: '' }" x-show="show" x-cloak @notify.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform translate-y-2">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-500 text-white px-5 py-4 rounded-lg shadow-xl flex items-center border-l-4 border-white">
            <i class="ri-information-line text-xl mr-3"></i>
            <span x-text="message" class="font-medium"></span>
        </div>
    </div>

    <!-- Loading animation component -->
    <div id="loading-spinner"
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden"
         x-data="{ show: false }"
         x-show="show"
         x-cloak
         @loading.window="show = $event.detail.show">
        <div class="bg-white rounded-lg p-5 flex flex-col items-center max-w-sm mx-auto">
            <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-indigo-600 mb-4"></div>
            <p class="text-gray-700 text-lg font-medium" x-text="$event.detail.message || 'Loading...'"></p>
        </div>
    </div>

    <!-- Back to top button -->
    <button id="back-to-top"
            class="fixed bottom-8 right-8 bg-indigo-600 text-white rounded-full p-2 shadow-lg z-40 hidden hover:bg-indigo-700 transition-all duration-300 transform hover:scale-110"
            onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <i class="ri-arrow-up-line text-lg"></i>
    </button>

    <!-- Custom scripts -->
    <script>
        // Function to show toast notifications
        window.notify = function(message) {
            window.dispatchEvent(new CustomEvent('notify', { detail: { message } }));
        }

        // Function to show/hide loading spinner
        window.showLoading = function(show = true, message = 'Loading...') {
            window.dispatchEvent(new CustomEvent('loading', {
                detail: { show, message }
            }));
        }

        // Back to top button functionality
        document.addEventListener('DOMContentLoaded', function() {
            const backToTopButton = document.getElementById('back-to-top');

            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTopButton.classList.remove('hidden');
                    backToTopButton.classList.add('flex');
                } else {
                    backToTopButton.classList.add('hidden');
                    backToTopButton.classList.remove('flex');
                }
            });
        });
    </script>
</body>
</html>
