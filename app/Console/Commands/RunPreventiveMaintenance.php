<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MaintenanceSchedule;
use App\Models\Ticket; // Assuming your Ticket model is here
use Carbon\Carbon;

class RunPreventiveMaintenance extends Command
{
    protected $signature = 'pms:run';
    protected $description = 'Check maintenance schedules and generate tickets';

    public function handle()
    {
        $today = Carbon::today();

        // 1. Find schedules due today (or before today if missed)
        $schedules = MaintenanceSchedule::whereDate('next_run_date', '<=', $today)->get();

        foreach ($schedules as $schedule) {
            // 2. Create the Ticket automatically
            Ticket::create([
                'title' => '[PMS] ' . $schedule->title,
                'description' => $schedule->description . "\n\n(Auto-generated Maintenance Ticket)",
                'status' => 'Open',
                'priority' => $schedule->priority,
                'category' => $schedule->category,
                'assigned_to' => $schedule->assigned_to, // Auto-assign to IT Staff
                'client_name' => 'System Automation',
                'department' => 'ICT Office',
                'created_at' => now(),
            ]);

            // 3. Calculate the NEXT run date
            $nextDate = Carbon::parse($schedule->next_run_date);

            switch ($schedule->frequency) {
                case 'daily': $nextDate->addDay(); break;
                case 'weekly': $nextDate->addWeek(); break;
                case 'monthly': $nextDate->addMonth(); break;
                case 'quarterly': $nextDate->addMonths(3); break;
                case 'yearly': $nextDate->addYear(); break;
            }

            // 4. Update the schedule
            $schedule->update(['next_run_date' => $nextDate]);

            $this->info("Generated ticket for: {$schedule->title}");
        }

        $this->info('PMS Run Completed.');
    }
}