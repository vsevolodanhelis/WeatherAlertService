<?php

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\WeatherController;
use App\Http\Controllers\Web\SubscriptionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;

// Home routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Weather routes
Route::get('/weather', [WeatherController::class, 'index'])->name('weather.index');
Route::get('/weather/show', [WeatherController::class, 'show'])->name('weather.show');

// Subscription routes
Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
Route::get('/subscriptions/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
Route::get('/subscriptions/success', [SubscriptionController::class, 'success'])->name('subscriptions.success');
Route::delete('/subscriptions/{id}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');

// Test email route
Route::get('/test-email', function () {
    try {
        Mail::to('test@example.com')->send(new TestEmail());
        return 'Email sent successfully! Check your logs.';
    } catch (\Exception $e) {
        return 'Failed to send email: ' . $e->getMessage();
    }
});
