<?php

namespace App\Http\Requests;

use App\Models\WeatherSubscription;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'city' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],
            'condition_type' => [
                'required',
                'string',
                Rule::in(array_keys(WeatherSubscription::CONDITION_TYPES)),
            ],
            'condition_value' => [
                'nullable',
                'numeric',
                Rule::requiredIf(function () {
                    return in_array($this->condition_type, [
                        'temperature_below',
                        'temperature_above',
                        'wind_speed_above',
                    ]);
                }),
            ],
        ];
    }
}
