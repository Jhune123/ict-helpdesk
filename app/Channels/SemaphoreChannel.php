<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SemaphoreChannel
{
    public function send($notifiable, Notification $notification)
{
    if (!method_exists($notification, 'toSms')) {
        return;
    }

    // 1. Try to get the number specifically for Semaphore
    if (method_exists($notifiable, 'routeNotificationForSemaphore')) {
        $phoneNumber = $notifiable->routeNotificationForSemaphore();
    } 
    // 2. Fallback: Check if there is a 'contact' column (for Tickets)
    elseif (!empty($notifiable->contact)) {
        $phoneNumber = $notifiable->contact;
    }
    // 3. Fallback: Check if there is a 'phone_number' column (for Users)
    elseif (!empty($notifiable->phone_number)) {
        $phoneNumber = $notifiable->phone_number;
    } else {
        Log::warning("Semaphore SMS Skipped: No phone number found for ID {$notifiable->id}");
        return;
    }

    // Clean the number (optional but good practice)
    // Ensure it starts with 09 or 639... (basic cleaning)

    $message = $notification->toSms($notifiable);

    try {
        $response = Http::post('https://api.semaphore.co/api/v4/messages', [
            'apikey'     => env('SEMAPHORE_API_KEY'),
            'number'     => $phoneNumber,
            'message'    => $message,
            'sendername' => env('SEMAPHORE_SENDER_NAME', 'SEMAPHORE')
        ]);

        if ($response->successful()) {
            Log::info("Semaphore SMS Sent to {$phoneNumber}: {$message}");
        } else {
            Log::error("Semaphore SMS Failed: " . $response->body());
        }

    } catch (\Exception $e) {
        Log::error("Semaphore SMS Connection Error: " . $e->getMessage());
    }
}
}