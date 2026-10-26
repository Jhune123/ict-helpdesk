<?php

namespace App\Notifications;

use App\Models\MaintenanceSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceRescheduledNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly MaintenanceSchedule $schedule
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'maintenance_id' => $this->schedule->id,
            'title' => 'Preventive Maintenance Rescheduled',
            'message' => "The next maintenance for {$this->schedule->title} is scheduled for {$this->schedule->next_run_date->format('F j, Y')}.",
            'action_url' => route('maintenance.show', $this->schedule),
            'action_label' => 'View Schedule',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Next maintenance schedule: '.$this->schedule->title)
            ->greeting('Hello '.$notifiable->name.',')
            ->line('The maintenance task has been marked done and the next schedule is set.')
            ->line('Equipment: '.$this->schedule->title)
            ->line('Office/College: '.$this->schedule->office_college)
            ->line('Next schedule: '.$this->schedule->next_run_date->format('F j, Y'))
            ->action('View Maintenance Schedule', route('maintenance.show', $this->schedule))
            ->line('Please prepare for the next scheduled maintenance.');
    }
}
