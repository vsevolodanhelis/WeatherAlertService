<?php

namespace App\Console\Commands;

use App\Models\NotificationLog;
use App\Models\WeatherSubscription;
use App\Notifications\WeatherAlertNotification;
use App\Services\WeatherService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckWeatherConditions extends Command
{
    protected $signature = 'weather:check-conditions';
    protected $description = 'Check weather conditions for all active subscriptions and send notifications if conditions are met';

    protected WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        parent::__construct();
        $this->weatherService = $weatherService;
    }

    public function handle()
    {
        $this->info('Starting to check weather conditions for all active subscriptions...');
        $subscriptions = WeatherSubscription::with(['user', 'city'])
            ->where('is_active', true)
            ->get();

        $this->info("Found {$subscriptions->count()} active subscriptions.");

        $notificationsSent = 0;

        foreach ($subscriptions as $subscription) {
            try {
                $this->info("Checking conditions for subscription #{$subscription->id} ({$subscription->city->name}, {$subscription->condition_type})");

                // Check if we've already sent a notification today for this subscription
                $lastNotification = NotificationLog::where('weather_subscription_id', $subscription->id)
                    ->whereDate('sent_at', Carbon::today())
                    ->first();

                if ($lastNotification) {
                    $this->info("Notification already sent today for subscription #{$subscription->id}. Skipping.");
                    continue;
                }

                // Check if the condition is met
                $result = $this->weatherService->isConditionMet(
                    $subscription->city->name,
                    $subscription->condition_type,
                    $subscription->condition_value
                );

                if ($result['met']) {
                    $this->info("Condition met for subscription #{$subscription->id}. Sending notification.");

                    // Send notification
                    $subscription->user->notify(new WeatherAlertNotification($subscription, $result));

                    // Log the notification
                    NotificationLog::create([
                        'weather_subscription_id' => $subscription->id,
                        'current_value' => $result['current_value'],
                        'message' => $result['message'] ?? 'Weather condition met',
                        'sent_at' => now(),
                    ]);

                    $notificationsSent++;
                } else {
                    $this->info("Condition not met for subscription #{$subscription->id}.");
                }
            } catch (\Exception $e) {
                $this->error("Error processing subscription #{$subscription->id}: {$e->getMessage()}");
                Log::error("Error in CheckWeatherConditions command: {$e->getMessage()}", [
                    'subscription_id' => $subscription->id,
                    'exception' => $e,
                ]);
            }
        }

        $this->info("Completed checking weather conditions. Sent {$notificationsSent} notifications.");
        return 0;
    }
}
