<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Models\City;
use App\Models\User;
use App\Models\WeatherSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    public function create()
    {
        return view('subscriptions.create', [
            'conditionTypes' => WeatherSubscription::CONDITION_TYPES,
        ]);
    }

    public function store(SubscriptionRequest $request)
    {
        $user = User::firstOrCreate(
            ['email' => $request->input('email')],
            ['name' => explode('@', $request->input('email'))[0], 'password' => bcrypt(Str::random(16))]
        );

        $cityName = $request->input('city');
        $city = City::firstOrCreate(
            ['name' => $cityName, 'country_code' => 'XX'],
            ['latitude' => 0, 'longitude' => 0]
        );

        $conditionType = $request->input('condition_type');
        $conditionValue = $request->input('condition_value');

        WeatherSubscription::updateOrCreate(
            [
                'user_id' => $user->id,
                'city_id' => $city->id,
                'condition_type' => $conditionType,
            ],
            [
                'email' => $request->input('email'),
                'condition_value' => $conditionValue,
                'is_active' => true,
            ]
        );

        session([
            'subscription_email' => $request->input('email'),
            'subscription_city' => $cityName,
            'subscription_condition' => $this->formatConditionForDisplay($conditionType, $conditionValue)
        ]);

        return redirect()->route('subscriptions.success');
    }

    private function formatConditionForDisplay(string $conditionType, ?float $conditionValue): string
    {
        $conditionTypes = WeatherSubscription::CONDITION_TYPES;

        if (!isset($conditionTypes[$conditionType])) {
            return 'Unknown condition';
        }

        $conditionName = $conditionTypes[$conditionType];

        if (in_array($conditionType, ['temperature_below', 'temperature_above'])) {
            return "{$conditionName}: {$conditionValue}°C";
        } elseif ($conditionType === 'wind_speed_above') {
            return "{$conditionName}: {$conditionValue} m/s";
        }

        return $conditionName;
    }

    public function success()
    {
        return view('subscriptions.success');
    }

    public function index(Request $request)
    {
        $email = $request->input('email');
        $subscriptions = collect();

        if ($email) {
            $subscriptions = WeatherSubscription::with('city')
                ->where('email', $email)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('subscriptions.index', [
            'email' => $email,
            'subscriptions' => $subscriptions,
            'conditionTypes' => WeatherSubscription::CONDITION_TYPES,
        ]);
    }

    public function destroy($id)
    {
        $subscription = WeatherSubscription::findOrFail($id);
        $email = $subscription->email;

        $subscription->delete();

        return redirect()->route('subscriptions.index', ['email' => $email])
            ->with('success', 'Subscription deleted successfully.');
    }
}
