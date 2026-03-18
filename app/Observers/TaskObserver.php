<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\Ticket;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // ✅ Added to catch silent crashes

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        try {
            // 1. Generate the next Ticket Number (KSU-ICTO-TIC-001 format) safely
            $lastTicket = Ticket::latest('id')->first();
            $nextNum = 1;
            
            if ($lastTicket && $lastTicket->ticket_number) {
                // Safely extract the number just in case the format changed
                preg_match('/\d+$/', $lastTicket->ticket_number, $matches);
                if (!empty($matches)) {
                    $nextNum = (int)$matches[0] + 1;
                }
            }
            $ticketNumber = 'KSU-ICTO-TIC-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

            // 2. Ensure a "Scheduled Maintenance" category exists
            $category = Category::firstOrCreate(['name' => 'Scheduled Maintenance']);

            // 3. Format the Task details into the Ticket Description
            $fullDescription = "Scheduled Task: " . $task->description . "\n";
            $fullDescription .= "Location: " . $task->location . "\n";
            $fullDescription .= "Scheduled Date: " . Carbon::parse($task->date)->format('F d, Y') . "\n";
            $fullDescription .= "Time: " . $task->start_time . " to " . $task->end_time . "\n";
            
            if ($task->remarks) {
                $fullDescription .= "Remarks: " . $task->remarks;
            }

            // 4. Automatically create the Ticket AND save it to a variable
            $ticket = Ticket::create([
                'ticket_number'   => $ticketNumber,
                'title'           => '[SCHEDULED] ' . substr($task->description, 0, 50),
                'description'     => $fullDescription,
                'equipment_type'  => 'N/A',
                'brand_model'     => 'N/A',
                'serial_no'       => 'N/A',
                'priority'        => 'Normal',
                'category_id'     => $category->id,
                // ✅ Safely check if department exists before getting its name to prevent crashes
                'department'      => $task->department ? $task->department->name : 'ICTO', 
                'status'          => 'Open',
                'client_name'     => $task->requested_by ?? 'N/A',
                'date_submitted'  => Carbon::now('Asia/Manila'),
                'created_by'      => Auth::id() ?? 1,
            ]);

            // 5. Link the ticket back to the task!
            // ✅ Save quietly prevents infinite loops without triggering other events
            $task->ticket_id = $ticket->id;
            $task->saveQuietly();
            
        } catch (\Exception $e) {
            // ✅ If it crashes, it will now tell us exactly WHY in your log files!
            Log::error('TaskObserver failed to create ticket: ' . $e->getMessage());
        }
    }
}