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

class TicketController extends Controller
{
    /**
     * Display all tickets
     */
    public function index()
    {
        $tickets = Ticket::with(['category', 'assignee'])
            ->orderBy('created_at', 'desc')
            ->get([
                'id', 'ticket_number', 'title', 'description', 'priority', 'status', 'remarks',
                'client_name', 'department', 'contact_number', 'assigned_to',
                'category_id', 'date_submitted', 'date_finished', 'created_at'
            ]);

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show create ticket form
     */
    public function create()
    {
        $categories = Category::all();
        $departments = Department::all();

        $it_personnel = User::role('it_staff')
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        return view('tickets.create', compact('categories', 'departments', 'it_personnel'));
    }

    /**
     * Store a new ticket
     */
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

        /**
         * AUTO GENERATE TICKET NUMBER
         * Format: KSU-ICTO-TIC-001
         */
        $lastTicket = Ticket::orderBy('id', 'desc')->first();

        if ($lastTicket && $lastTicket->ticket_number) {
            $lastNumber = (int) substr($lastTicket->ticket_number, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = "001";
        }

        $ticketNumber = "KSU-ICTO-TIC-" . $newNumber;

        // Handle manual category
        $categoryId = $validated['category_id'] ?? null;
        if (!$categoryId && !empty($validated['category_manual'])) {
            $category = Category::firstOrCreate(['name' => $validated['category_manual']]);
            $categoryId = $category->id;
        }

        // Handle manual department
        $departmentName = $validated['department'] ?? null;
        if (!empty($validated['department_manual'])) {
            $departmentName = $validated['department_manual'];
        }

        // Create ticket
        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'] ?? 'Normal',
            'category_id' => $categoryId,
            'department' => $departmentName,
            'assigned_to' => $validated['assigned_to'] ?? null,
            'status' => 'Open',
            'remarks' => $validated['remarks'] ?? null,
            'client_name' => $validated['client_name'],
            'contact_number' => $validated['contact_number'] ?? null,
            'date_submitted' => Carbon::now('Asia/Manila'),
            'created_by' => Auth::id(),
        ]);

        // Notify IT personnel
        if (!empty($validated['assigned_to'])) {
            $it = User::find($validated['assigned_to']);
            if ($it) {
                $it->notify(new TicketAssignedNotification($ticket));
            }
        }

        return redirect()->route('tickets.index')
            ->with('success', "Ticket created successfully ✅ Ticket Number: $ticketNumber");
    }

    /**
     * Show ticket details
     */
    public function show(Ticket $ticket)
    {
        $ticket->load(['category', 'assignee']);
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Edit ticket form
     */
    public function edit(Ticket $ticket)
    {
        $categories = Category::all();
        $departments = Department::all();

        $it_personnel = User::role('it_staff')
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        return view('tickets.edit', compact('ticket', 'categories', 'departments', 'it_personnel'));
    }

    /**
     * Update ticket
     */
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
        ]);

        // Manual category
        $categoryId = $validated['category_id'] ?? null;
        if (!$categoryId && !empty($validated['category_manual'])) {
            $category = Category::firstOrCreate(['name' => $validated['category_manual']]);
            $categoryId = $category->id;
        }

        // Manual department
        $departmentName = $validated['department'] ?? null;
        if (!empty($validated['department_manual'])) {
            $departmentName = $validated['department_manual'];
        }

        // Update data
        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'] ?? 'Normal',
            'category_id' => $categoryId,
            'department' => $departmentName,
            'assigned_to' => $validated['assigned_to'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
        ];

        // Status + auto date finished
        if (!empty($validated['status'])) {
            $data['status'] = $validated['status'];
            $data['date_finished'] =
                $validated['status'] === 'Closed' ? Carbon::now('Asia/Manila') : null;
        }

        $ticket->update($data);

        // Notify IT personnel
        if (!empty($validated['assigned_to'])) {
            $it = User::find($validated['assigned_to']);
            if ($it) {
                $it->notify(new TicketAssignedNotification($ticket));
            }
        }

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket updated successfully ✅');
    }

    /**
     * Delete ticket
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')
            ->with('success', 'Ticket deleted successfully ❌');
    }

    /**
     * My Tickets (client/employee)
     */
    public function mine()
    {
        $tickets = Ticket::with(['category'])
            ->where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tickets.mine', compact('tickets'));
    }

    /**
     * Group by Department
     */
    public function byDepartment()
    {
        $tickets = Ticket::with(['category'])
            ->orderBy('department')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('department');

        return view('tickets.departments', compact('tickets'));
    }

    /**
     * EXPORT (CSV, EXCEL, PDF)
     */
    public function export($type)
    {
        $fileName = 'tickets_' . now()->format('Ymd_His');

        if ($type === 'csv' || $type === 'xlsx') {
            return Excel::download(new TicketsExport, $fileName . '.' . $type);
        }

        if ($type === 'pdf') {
            $tickets = Ticket::with(['category', 'assignee'])->get();
            $pdf = Pdf::loadView('tickets.pdf', compact('tickets'));
            return $pdf->download($fileName . '.pdf');
        }

        return redirect()->back()->with('error', 'Export type not supported');
    }

    /**
     * JOB ORDER PDF GENERATOR
     */
    public function jobOrderPdf(Ticket $ticket)
    {
        $ticket->load(['category', 'assignee']);

        $pdf = Pdf::loadView('tickets.job_order', compact('ticket'))
            ->setPaper('A4', 'portrait');

        $fileName = 'JobOrder-' . $ticket->ticket_number . '.pdf';

        return $pdf->download($fileName);
    }
}
