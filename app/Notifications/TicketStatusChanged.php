<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use App\Channels\SemaphoreChannel;

class TicketStatusChanged extends Notification
{
    use Queueable;

    public $status;

    public function __construct($status)
    {
        $this->status = $status;
    }

    /**
     * 🧠 SMART CHANNEL SELECTION
     * Decides: Send Email OR Send SMS?
     */
    public function via($notifiable)
    {
        // 1. Get the Contact Info (Phone or Email)
        $contact = $notifiable->contact_number;

        Log::info("🔔 Notification Triggered for Ticket #{$notifiable->ticket_number}");

        // 2. Check if it is a valid Email Address
        if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            Log::info("✅ It is an EMAIL ({$contact}). Sending via Mail.");
            return ['mail'];
        } 

        // 3. Check if it is a Phone Number (Not empty and not 'N/A')
        if (!empty($contact) && $contact !== 'N/A') {
            Log::info("✅ It is a PHONE NUMBER ({$contact}). Sending via Semaphore.");
            return [SemaphoreChannel::class];
        }

        // 4. Fallback (No valid contact info)
        Log::warning("⚠️ No valid contact info found. Notification skipped.");
        return [];
    }

    /**
     * 📧 EMAIL FORMAT (Customized for Closed/Condemned)
     */
    public function toMail($notifiable)
    {
        $mail = new MailMessage;
        
        // 🛑 CONDEMNED MESSAGE
        if ($this->status === 'Condemned') {
            return $mail
                ->subject('⚠️ Equipment Condemned: Ticket ' . $notifiable->ticket_number)
                ->greeting('Hello ' . $notifiable->client_name . ',')
                ->line('We are writing to inform you about the status of your equipment.')
                ->line('🛑 Status: CONDEMNED')
                ->line('After technical evaluation, the equipment has been deemed unserviceable or beyond economic repair.')
                ->line('📝 Remarks: ' . ($notifiable->remarks ?? 'See report for details.'))
                ->action('View Details', url('/tickets/' . $notifiable->id))
                ->line('Please contact the ICT Office if you have questions regarding the replacement process.');
        }

        // ✅ CLOSED MESSAGE
        if ($this->status === 'Closed') {
            return $mail
                ->subject('✅ Ticket Closed: ' . $notifiable->ticket_number)
                ->greeting('Hello ' . $notifiable->client_name . ',')
                ->line('Great news! Your support ticket has been resolved.')
                ->line('✅ Status: CLOSED')
                ->line('We hope we were able to assist you with your concern.')
                ->line('📝 Remarks: ' . ($notifiable->remarks ?? 'Issue resolved.'))
                ->action('View Ticket', url('/tickets/' . $notifiable->id))
                ->line('Thank you for trusting the KSU ICT Helpdesk!');
        }

        // ℹ️ GENERIC MESSAGE (For Open, Pending, On-Going)
        return $mail
            ->subject('Ticket Update: ' . $notifiable->ticket_number)
            ->greeting('Hello ' . $notifiable->client_name . ',')
            ->line('The status of your ticket has been updated.')
            ->line('🔄 New Status: ' . strtoupper($this->status))
            ->line('📝 Remarks: ' . ($notifiable->remarks ?? 'None'))
            ->action('View Ticket', url('/tickets/' . $notifiable->id));
    }

    /**
     * 📱 SMS FORMAT (Simplified for Text)
     */
    public function toSemaphore($notifiable)
    {
        $statusUpper = strtoupper($this->status);
        return "Hi {$notifiable->client_name}, Ticket {$notifiable->ticket_number} is now {$statusUpper}. check email for details.";
    }
}