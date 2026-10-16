<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\User;
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

            // 4. Automatically create the Ticket AND save it to a variable
            $ticket = Ticket::create(array_merge($this->ticketData($task), [
                'ticket_number'  => $ticketNumber,
                'equipment_type' => 'N/A',
                'brand_model'    => 'N/A',
                'serial_no'      => 'N/A',
                'priority'       => 'Normal',
                'status'         => 'Open',
                'date_submitted' => Carbon::now('Asia/Manila'),
                'created_by'     => Auth::id() ?? 1,
            ]));

            // 5. Link the ticket back to the task!
            // ✅ Save quietly prevents infinite loops without triggering other events
            $task->ticket_id = $ticket->id;
            $task->saveQuietly();
            
        } catch (\Exception $e) {
            // ✅ If it crashes, it will now tell us exactly WHY in your log files!
            Log::error('TaskObserver failed to create ticket: ' . $e->getMessage());
        }
    }

    public function updated(Task $task): void
    {
        if (!$task->wasChanged([
            'title',
            'date',
            'description',
            'requested_by',
            'department_id',
            'category_id',
            'location',
            'start_time',
            'end_time',
            'assigned_to',
            'remarks',
        ])) {
            return;
        }

        $task->unsetRelation('department');
        $task->unsetRelation('category');
        $task->ticket?->update($this->ticketData($task));
    }

    private function ticketData(Task $task): array
    {
        $departmentName = $task->department?->name ?? 'ICTO';
        $category = $task->category ?? Category::firstOrCreate(['name' => 'Scheduled Maintenance']);
        $assignedUserId = $task->assigned_to
            ? User::where('name', $task->assigned_to)->value('id')
            : null;
        $ticketDescription = implode("\n", [
            $task->description,
            'Location: ' . $task->location,
            'Schedule Date: ' . Carbon::parse($task->date)->format('F d, Y'),
            'Time: ' . $task->start_time . ' to ' . $task->end_time,
        ]);

        return [
            'title'       => $task->title ?: '[SCHEDULED] ' . substr($task->description, 0, 50),
            'description' => $ticketDescription,
            'department'  => $departmentName,
            'category_id' => $category->id,
            'client_name' => $task->requested_by ?? 'N/A',
            'assigned_to' => $assignedUserId,
            'remarks'     => $task->remarks,
            'form_data'   => [
                'form_type'  => 'scheduled_task',
                'task_date'  => $task->date,
                'location'   => $task->location,
                'start_time' => $task->start_time,
                'end_time'   => $task->end_time,
                'category'   => $category->name,
            ],
        ];
    }
}