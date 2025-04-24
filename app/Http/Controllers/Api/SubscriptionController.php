<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Models\City;
use App\Models\User;
use App\Models\WeatherSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    public function store(SubscriptionRequest $request): JsonResponse
    {
        try {
            $conditionType = $request->input('condition_type');
            $conditionValue = $request->input('condition_value');

            $requiresValue = in_array($conditionType, ['temperature_below', 'temperature_above', 'wind_speed_above']);
            if ($requiresValue && ($conditionValue === null || $conditionValue === '')) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => [
                        'condition_value' => ['Condition value is required for ' . $conditionType]
                    ]
                ], 422);
            }

            $user = User::firstOrCreate(
                ['email' => $request->input('email')],
                [
                    'name' => explode('@', $request->input('email'))[0],
                    'password' => bcrypt(Str::random(16))
                ]
            );

            $city = City::firstOrCreate(
                ['name' => $request->input('city'), 'country_code' => 'XX'],
                ['latitude' => 0, 'longitude' => 0]
            );

            $subscription = WeatherSubscription::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'city_id' => $city->id,
                    'condition_type' => $request->input('condition_type'),
                ],
                [
                    'email' => $request->input('email'),
                    'condition_value' => $request->input('condition_value'),
                    'is_active' => true,
                ]
            );

            return response()->json([
                'data' => [
                    'id' => $subscription->id,
                    'email' => $subscription->email,
                    'city' => $city->name,
                    'condition_type' => $subscription->condition_type,
                    'condition_value' => $subscription->condition_value,
                    'created_at' => $subscription->created_at,
                ],
                'message' => 'Subscription created successfully',
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create subscription: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to create subscription',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
