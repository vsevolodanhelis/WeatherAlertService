<?php

use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\WeatherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Weather routes
Route::get('/weather', [WeatherController::class, 'getCurrentWeather']);

// Subscription routes
Route::post('/subscriptions', [SubscriptionController::class, 'store']);
