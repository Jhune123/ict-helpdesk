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
     */
    public function via($notifiable)
    {
        // Clean the contact info (remove spaces)
        $contact = trim($notifiable->contact_number);

        Log::info("🔔 Notification Triggered for Ticket #{$notifiable->ticket_number}");

        // 1. Check if it is a valid Email Address
        if (filter_var($contact, FILTER_VALIDATE_EMAIL)) {
            Log::info("✅ It is an EMAIL ({$contact}). Sending via Mail.");
            return ['mail'];
        } 

        // 2. Check if it is a Phone Number
        if (!empty($contact) && $contact !== 'N/A') {
            Log::info("✅ It is a PHONE NUMBER ({$contact}). Sending via Semaphore.");
            return [SemaphoreChannel::class];
        }

        return [];
    }

    /**
     * 📧 EMAIL FORMAT
     */
    public function toMail($notifiable)
    {
        $mail = new MailMessage;

        // Custom "From" Name (Optional override)
        $mail->from(env('MAIL_FROM_ADDRESS'), 'KSU ICT Helpdesk');

        // 🛑 CONDEMNED MESSAGE
        if ($this->status === 'Condemned') {
            return $mail
                ->subject('⚠️ Equipment Condemned: Ticket ' . $notifiable->ticket_number)
                ->greeting('Hello ' . $notifiable->client_name . ',')
                ->line('We are writing to inform you about the status of your equipment.')
                ->line('🛑 Status: CONDEMNED')
                ->line('After technical evaluation, the equipment has been deemed unserviceable.')
                ->line('📝 Remarks: ' . ($notifiable->remarks ?? 'See report for details.'))
                ->action('View Details', url('/tickets/' . $notifiable->id))
                ->salutation("Regards,\nKSU ICT Helpdesk");
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
                ->salutation("Regards,\nKSU ICT Helpdesk");
        }

        // ℹ️ GENERIC MESSAGE (Open, Pending, etc.)
        return $mail
            ->subject('Ticket Update: ' . $notifiable->ticket_number)
            ->greeting('Hello ' . $notifiable->client_name . ',')
            ->line('The status of your ticket has been updated.')
            ->line('🔄 New Status: ' . strtoupper($this->status))
            ->line('📝 Remarks: ' . ($notifiable->remarks ?? 'None'))
            ->action('View Ticket', url('/tickets/' . $notifiable->id))
            ->salutation("Regards,\nKSU ICT Helpdesk");
    }

    /**
     * 📱 SMS FORMAT
     */
    public function toSemaphore($notifiable)
    {
        $statusUpper = strtoupper($this->status);
        return "Hi {$notifiable->client_name}, Ticket {$notifiable->ticket_number} is now {$statusUpper}. Check email for details.";
    }

} // <--- IMPORTANT: This must be the very last character of the file