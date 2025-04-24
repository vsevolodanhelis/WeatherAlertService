<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Alert for {{ $city }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9fafb;
        }
        .header {
            background-color: #3b82f6;
            background-image: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            padding: 25px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .content {
            padding: 25px;
            background-color: white;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .alert-info {
            background-color: #f0f9ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .alert-value {
            font-size: 32px;
            font-weight: bold;
            color: #2563eb;
            text-align: center;
            margin: 15px 0;
        }
        .condition-badge {
            display: inline-block;
            background-color: #dbeafe;
            color: #1e40af;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            margin-right: 5px;
        }
        .btn {
            display: inline-block;
            background-color: #3b82f6;
            background-image: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: 600;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.2s ease;
        }
        .btn:hover {
            background-image: linear-gradient(135deg, #2563eb, #1d4ed8);
            box-shadow: 0 4px 6px rgba(0,0,0,0.12);
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 13px;
            color: #6b7280;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .tracking {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 10px;
        }
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            .header, .content {
                padding: 15px;
            }
            .alert-value {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Weather Alert Service</h1>
    </div>
    <div class="content">
        <h2>Weather Alert for {{ $city }}</h2>
        <p>Hello!</p>
        <p>We're sending you this alert because a weather condition you subscribed to has been met in <strong>{{ $city }}</strong>.</p>
        <p>{{ $message }}</p>

        <div class="alert-info">
            <p><strong>Current Weather Condition:</strong></p>
            <div class="alert-value">{{ $formattedCurrentValue ?? $currentValue }}</div>
            <p>
                <span class="condition-badge">{{ $conditionType }}</span>
                @if($conditionValue)
                    <span class="condition-badge">Threshold: {{ $conditionValue }}</span>
                @endif
            </p>
        </div>

        <p>Click the button below to view the current weather forecast for {{ $city }}:</p>
        <a href="{{ $weatherUrl ?? url('/weather/show?city='.$city) }}" class="btn">View Weather Forecast</a>

        <p>You can manage your weather alert subscriptions by visiting our website.</p>

        <p>Thank you for using our Weather Alert Service!</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} Weather Alert Service</p>
        <p>This is an automated message, please do not reply.</p>
        <p class="tracking">Reference: {{ $trackingId ?? substr(md5(time()), 0, 8) }}</p>
    </div>
</body>
</html>
