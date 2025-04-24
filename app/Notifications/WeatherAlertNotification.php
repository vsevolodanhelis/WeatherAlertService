<?php

namespace App\Notifications;

use App\Models\WeatherSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WeatherAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected WeatherSubscription $subscription;
    protected array $weatherData;

    public function __construct(WeatherSubscription $subscription, array $weatherData)
    {
        $this->subscription = $subscription;
        $this->weatherData = $weatherData;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $city = $this->subscription->city->name;
        $conditionType = $this->subscription->condition_type;
        $conditionValue = $this->subscription->condition_value;
        $currentValue = $this->weatherData['current_value'] ?? null;
        $message = $this->weatherData['message'] ?? 'Weather condition has been met.';

        $mailMessage = (new MailMessage)
            ->subject("Weather Alert for {$city}")
            ->greeting("Hello!")
            ->line("This is an automated alert from the Weather Alert Service.")
            ->line("Your weather condition for {$city} has been met:")
            ->line($message);

        if ($currentValue !== null) {
            $mailMessage->line("Current value: {$currentValue}");
        }

        $mailMessage->line("You are receiving this notification because you subscribed to be alerted when this weather condition occurs.")
            ->action('View Weather', url('/weather?city=' . urlencode($city)))
            ->line('Thank you for using our Weather Alert Service!');

        return $mailMessage;
    }

    public function toArray($notifiable): array
    {
        return [
            'subscription_id' => $this->subscription->id,
            'city' => $this->subscription->city->name,
            'condition_type' => $this->subscription->condition_type,
            'condition_value' => $this->subscription->condition_value,
            'current_value' => $this->weatherData['current_value'] ?? null,
            'message' => $this->weatherData['message'] ?? 'Weather condition has been met.',
        ];
    }
}
