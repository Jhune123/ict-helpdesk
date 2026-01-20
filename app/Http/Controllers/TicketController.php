<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Notifications\TicketAssignedNotification;
use App\Helpers\ActivityLogger;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['category', 'assignee'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        // ✅ ASCENDING ORDER (A–Z)
        $categories = Category::orderBy('name', 'asc')->get();
        $departments = Department::orderBy('name', 'asc')->get();

        $it_personnel = User::role('it_staff')
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        return view('tickets.create', compact(
            'categories',
            'departments',
            'it_personnel'
        ));
    }

    public function edit(Ticket $ticket)
    {
        // ✅ ASCENDING ORDER (A–Z)
        $categories = Category::orderBy('name', 'asc')->get();
        $departments = Department::orderBy('name', 'asc')->get();

        $it_personnel = User::role('it_staff')
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        return view('tickets.edit', compact(
            'ticket',
            'categories',
            'departments',
            'it_personnel'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'nullable|string|max:50',
            'category_id' => 'nullable|integer',
            'category_manual' => 'nullable|string|max:255',
            'client_name' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'department_manual' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'assigned_to' => 'nullable|integer',
            'remarks' => 'nullable|string|max:500',
        ]);

        /* 🎫 Ticket Number */
        $last = Ticket::latest()->first();
        $next = $last
            ? str_pad((int) substr($last->ticket_number, -3) + 1, 3, '0', STR_PAD_LEFT)
            : '001';

        $ticketNumber = 'KSU-ICTO-TIC-' . $next;

        /* 📂 Category (auto-save manual) */
        $categoryId = $validated['category_id'] ?? null;
        if (!$categoryId && !empty($validated['category_manual'])) {
            $categoryId = Category::firstOrCreate([
                'name' => $validated['category_manual']
            ])->id;
        }

        /* 🏢 Department (AUTO-SAVE manual input) */
        $departmentName = $validated['department_manual']
            ?? $validated['department']
            ?? null;

        $department = $departmentName
            ? Department::firstOrCreate(['name' => $departmentName])->name
            : null;

        /* 🔒 Assignment */
        $assignedTo = null;
        if (Auth::user()->hasRole(['admin', 'it_staff'])) {
            $assignedTo = $validated['assigned_to'] ?? null;
        }

        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'] ?? 'Normal',
            'category_id' => $categoryId,
            'department' => $department,
            'assigned_to' => $assignedTo,
            'status' => 'Open',
            'remarks' => $validated['remarks'] ?? null,
            'client_name' => $validated['client_name'],
            'contact_number' => $validated['contact_number'] ?? null,
            'date_submitted' => Carbon::now('Asia/Manila'),
            'created_by' => Auth::id(),
        ]);

        /* 🧾 Activity Log */
        ActivityLogger::log(
            'created',
            $ticket,
            'Created Ticket: "' . $ticket->title . '"'
        );

        /* 🔔 Notify IT */
        if ($assignedTo && ($it = User::find($assignedTo))) {
            $it->notify(new TicketAssignedNotification($ticket));

            ActivityLogger::log(
                'assigned',
                $ticket,
                'Assigned Ticket to ' . $it->name
            );
        }

        return redirect()->route('tickets.index')
            ->with('success', "Ticket created successfully ✅ Ticket No: {$ticketNumber}");
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['category', 'assignee']);
        return view('tickets.show', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'nullable|string|max:50',
            'category_id' => 'nullable|integer',
            'category_manual' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'department_manual' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|integer',
            'contact_number' => 'nullable|string|max:20',
            'remarks' => 'nullable|string|max:500',
            'status' => 'required|string',
            'client_name' => 'required|string|max:255',
        ]);

        $oldStatus = $ticket->status;
        $oldAssignee = $ticket->assigned_to;

        /* 📂 Category (auto-save manual) */
        $categoryId = $validated['category_id'] ?? null;
        if (!$categoryId && !empty($validated['category_manual'])) {
            $categoryId = Category::firstOrCreate([
                'name' => $validated['category_manual']
            ])->id;
        }

        /* 🏢 Department (AUTO-SAVE manual input) */
        $departmentName = $validated['department_manual']
            ?? $validated['department']
            ?? null;

        $department = $departmentName
            ? Department::firstOrCreate(['name' => $departmentName])->name
            : null;

        $newAssigned = Auth::user()->hasRole(['admin', 'it_staff'])
            ? $validated['assigned_to']
            : $ticket->assigned_to;

        $ticket->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'] ?? 'Normal',
            'category_id' => $categoryId,
            'department' => $department,
            'assigned_to' => $newAssigned,
            'contact_number' => $validated['contact_number'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
            'client_name' => $validated['client_name'],
            'status' => $validated['status'],
            'date_finished' => $validated['status'] === 'Closed'
                ? Carbon::now('Asia/Manila')
                : null,
        ]);

        /* 🧾 Activity Logs */
        ActivityLogger::log(
            'updated',
            $ticket,
            'Updated Ticket: "' . $ticket->title . '"'
        );

        if ($oldStatus !== $validated['status']) {
            ActivityLogger::log(
                'status_changed',
                $ticket,
                'Changed status from ' . $oldStatus . ' to ' . $validated['status']
            );
        }

        if ($oldAssignee != $newAssigned && $newAssigned) {
            if ($it = User::find($newAssigned)) {
                $it->notify(new TicketAssignedNotification($ticket));

                ActivityLogger::log(
                    'assigned',
                    $ticket,
                    'Reassigned Ticket to ' . $it->name
                );
            }
        }

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket updated successfully ✅');
    }

    public function destroy(Ticket $ticket)
    {
        ActivityLogger::log(
            'deleted',
            $ticket,
            'Deleted Ticket: "' . $ticket->title . '"'
        );

        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket deleted successfully ❌');
    }

    public function mine()
    {
        $tickets = Ticket::with('category')
            ->where('created_by', Auth::id())
            ->latest()
            ->paginate(10);

        return view('tickets.mine', compact('tickets'));
    }

    public function byDepartment()
    {
        $tickets = Ticket::with('category')
            ->latest()
            ->get()
            ->groupBy('department');

        return view('tickets.departments', compact('tickets'));
    }

    public function export($type)
    {
        $file = 'tickets_' . now()->format('Ymd_His');

        if (in_array($type, ['csv', 'xlsx'])) {
            return Excel::download(new TicketsExport, "{$file}.{$type}");
        }

        if ($type === 'pdf') {
            $tickets = Ticket::with(['category', 'assignee'])->get();
            return Pdf::loadView('tickets.pdf', compact('tickets'))
                ->download("{$file}.pdf");
        }

        return back()->with('error', 'Export type not supported');
    }

    public function jobOrderPdf(Ticket $ticket)
    {
        $ticket->load(['category', 'assignee']);

        return Pdf::loadView('tickets.job_order', compact('ticket'))
            ->setPaper('A4')
            ->download('JobOrder-' . $ticket->ticket_number . '.pdf');
    }
}
