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
        $email = $notifiable->routeNotificationForMail($this);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::info("Ticket status email notification selected for {$notifiable->ticket_number}.");
            return ['mail'];
        }

        $contact = $notifiable->getRawOriginal('contact_number');
        Log::info("Ticket status notification triggered for #{$notifiable->ticket_number}.");

        // Fall back to SMS when the client has no available email address.
        if (!empty($contact) && $contact !== 'N/A') {
            Log::info("Ticket status SMS notification selected for #{$notifiable->ticket_number}.");
            return [SemaphoreChannel::class];
        }

        Log::warning("No valid client contact information for ticket #{$notifiable->ticket_number}.");
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
        if (strtolower($this->status) === 'closed') {
            return $mail
                ->subject('✅ Ticket Closed: ' . $notifiable->ticket_number)
                ->greeting('Hello ' . $notifiable->client_name . ',')
                ->line('Great news! Your support ticket has been resolved.')
                ->line('✅ Status: CLOSED')
                ->line('Assigned IT staff: ' . ($notifiable->assignee?->name ?? 'ICT Helpdesk staff'))
                ->line('We hope we were able to assist you with your concern.')
                ->line('📝 Remarks: ' . ($notifiable->remarks ?? 'Issue resolved.'))
                ->action('View Ticket', url('/tickets/' . $notifiable->id))
                ->line('Thank you for trusting the KSU ICT Helpdesk!');
        }

        // Generic status updates also remind clients how to contact their assignee.
        return $mail
            ->subject('Ticket Update: ' . $notifiable->ticket_number)
            ->greeting('Hello ' . $notifiable->client_name . ',')
            ->line('The status of your ticket has been updated.')
            ->line('🔄 New Status: ' . strtoupper($this->status))
            ->line('Assigned IT staff: ' . ($notifiable->assignee?->name ?? 'ICT Helpdesk staff'))
            ->line('To request another status update or share information, open your ticket and message the assigned IT staff member in the Messages section.')
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