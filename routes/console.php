<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\MaintenanceSchedule;
use App\Models\Ticket;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * PREVENTIVE MAINTENANCE SCHEDULER (PMS) COMMAND
 * Usage: php artisan pms:run
 */
Artisan::command('pms:run', function () {
    // Get today's date
    $today = Carbon::today();
    $count = 0;

    $this->info('Checking for maintenance schedules due on or before: ' . $today->toDateString());

    // 1. Find schedules that are due today (or missed previously)
    // Make sure you have imported App\Models\MaintenanceSchedule at the top
    $schedules = MaintenanceSchedule::whereDate('next_run_date', '<=', $today)->get();

    if ($schedules->isEmpty()) {
        $this->info('No maintenance due today.');
        return;
    }

    foreach ($schedules as $schedule) {
        // 2. Automatically Create the Ticket
        Ticket::create([
            'title'       => '[PMS] ' . $schedule->title,
            'description' => $schedule->description . "\n\n(This is an Auto-generated Preventive Maintenance Ticket)",
            'status'      => 'Open',
            'priority'    => $schedule->priority ?? 'Normal',
            'category'    => $schedule->category ?? 'Maintenance',
            'assigned_to' => $schedule->assigned_to, // Auto-assign to the specific IT Staff
            'client_name' => 'System Automation',
            'department'  => 'ICT Office',
            'created_at'  => now(),
        ]);

        // 3. Calculate the NEXT run date based on frequency
        $nextDate = Carbon::parse($schedule->next_run_date);

        switch ($schedule->frequency) {
            case 'daily': 
                $nextDate->addDay(); 
                break;
            case 'weekly': 
                $nextDate->addWeek(); 
                break;
            case 'monthly': 
                $nextDate->addMonth(); 
                break;
            case 'quarterly': 
                $nextDate->addMonths(3); 
                break;
            case 'yearly': 
                $nextDate->addYear(); 
                break;
            default:
                $nextDate->addMonth(); // Default fallback
                break;
        }

        // 4. Update the schedule with the new date
        $schedule->update(['next_run_date' => $nextDate]);
        
        $this->info("✅ Generated ticket for: {$schedule->title}");
        $count++;
    }

    $this->info("PMS Run Completed. {$count} tickets generated.");

})->purpose('Check for due maintenance schedules and auto-generate tickets');

// AUTOMATIC SCHEDULING (For Laravel 11+)
// If you are using Laravel 11, the schedule is defined right here.
// If you are using Laravel 10 or below, you must copy the line below into app/Console/Kernel.php
if (class_exists(Schedule::class)) {
    Schedule::command('pms:run')->dailyAt('08:00');
}