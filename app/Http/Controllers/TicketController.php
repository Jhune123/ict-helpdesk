<?php

namespace App\Http\Controllers;

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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketsExport;

class TicketController extends Controller
{
    // 📝 Ticket Management Index with Pagination + Search
    public function index(Request $request)
    {
        $query = Ticket::with(['category', 'assignee'])->orderBy('created_at', 'desc');

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('priority', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }

        if ($request->filled('month')) {
            $query->whereMonth('date_submitted', $request->month);
        }
        if ($request->filled('year')) {
            $query->whereYear('date_submitted', $request->year);
        }

        $tickets = $query->paginate(20)->withQueryString();
        return view('tickets.index', compact('tickets'));
    }

    // ➕ Create Ticket Form
    public function create()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        $departments = Department::orderBy('name', 'asc')->get();
        $it_personnel = User::role('it_staff')->select('id', 'name')->orderBy('name')->get();

        return view('tickets.create', compact('categories', 'departments', 'it_personnel'));
    }

    // ✏ Edit Ticket Form
    public function edit(Ticket $ticket)
    {
        $categories = Category::orderBy('name', 'asc')->get();
        $departments = Department::orderBy('name', 'asc')->get();
        $it_personnel = User::role('it_staff')->select('id', 'name')->orderBy('name', 'asc')->get();

        return view('tickets.edit', compact('ticket', 'categories', 'departments', 'it_personnel'));
    }

    // 💾 Store New Ticket
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'nullable|string|max:50',
            'category_id' => 'nullable|integer',
            'category_manual' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|integer',
            'client_name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'remarks' => 'nullable|string|max:500',
            'attachment.*' => 'nullable|file|max:5120',
        ]);

        $last = Ticket::latest()->first();
        $next = $last ? str_pad((int) substr($last->ticket_number, -3) + 1, 3, '0', STR_PAD_LEFT) : '001';
        $ticketNumber = 'KSU-ICTO-TIC-' . $next;

        $categoryId = $validated['category_id'] ?? null;
        if (!$categoryId && !empty($validated['category_manual'])) {
            $categoryId = Category::firstOrCreate(['name' => $validated['category_manual']])->id;
        }

        $departmentName = $validated['department_manual'] ?? $validated['department'] ?? null;
        $department = $departmentName ? Department::firstOrCreate(['name' => $departmentName])->name : null;

        $assignedTo = Auth::user()->hasRole(['admin', 'it_staff']) ? $validated['assigned_to'] ?? null : null;

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

        ActivityLogger::log('created', $ticket, 'Created Ticket: "' . $ticket->title . '"');

        if ($assignedTo && ($it = User::find($assignedTo))) {
            $it->notify(new TicketAssignedNotification($ticket));
            ActivityLogger::log('assigned', $ticket, 'Assigned Ticket to ' . $it->name);
        }

        return redirect()->route('tickets.index')->with('success', "Ticket created successfully ✅ ({$ticketNumber})");
    }

    // 👁 Show Ticket Details with Comments & Attachments
    public function show(Ticket $ticket)
    {
        $ticket->load(['comments.user', 'attachments', 'assignee', 'category']);
        return view('tickets.show', compact('ticket'));
    }

    // 🔄 Update Ticket
    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'nullable|string|max:50',
            'category_id' => 'nullable|integer',
            'category_manual' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|integer',
            'client_name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'remarks' => 'nullable|string|max:500',
            'status' => 'required|string',
            'attachment.*' => 'nullable|file|max:5120',
        ]);

        $oldAssignee = $ticket->assigned_to;
        $oldStatus = $ticket->status;

        $categoryId = $validated['category_id'] ?? null;
        if (!$categoryId && !empty($validated['category_manual'])) {
            $categoryId = Category::firstOrCreate(['name' => $validated['category_manual']])->id;
        }

        $departmentName = $validated['department_manual'] ?? $validated['department'] ?? null;
        $department = $departmentName ? Department::firstOrCreate(['name' => $departmentName])->name : null;

        $assignedTo = Auth::user()->hasRole(['admin', 'it_staff']) ? $validated['assigned_to'] : $ticket->assigned_to;

        $ticket->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'] ?? 'Normal',
            'category_id' => $categoryId,
            'department' => $department,
            'assigned_to' => $assignedTo,
            'client_name' => $validated['client_name'],
            'contact_number' => $validated['contact_number'],
            'remarks' => $validated['remarks'],
            'status' => $validated['status'],
            'date_finished' => $validated['status'] === 'Closed' ? Carbon::now('Asia/Manila') : null,
        ]);

        ActivityLogger::log('updated', $ticket, 'Updated Ticket: "' . $ticket->title . '"');

        if ($oldStatus !== $validated['status']) {
            ActivityLogger::log('status_changed', $ticket, 'Changed status from ' . $oldStatus . ' to ' . $validated['status']);
        }

        if ($oldAssignee != $assignedTo && $assignedTo) {
            if ($it = User::find($assignedTo)) {
                $it->notify(new TicketAssignedNotification($ticket));
                ActivityLogger::log('assigned', $ticket, 'Reassigned Ticket to ' . $it->name);
            }
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully ✅');
    }

    // 🗑 Delete Ticket
    public function destroy(Ticket $ticket)
    {
        ActivityLogger::log('deleted', $ticket, 'Deleted Ticket: "' . $ticket->title . '"');
        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully ❌');
    }

    // 🧑‍💻 Tickets Created by Logged-in User
    public function mine()
    {
        $tickets = Ticket::where('created_by', Auth::id())->latest()->paginate(20);
        return view('tickets.mine', compact('tickets'));
    }

    // 🏢 Tickets Grouped by Department
    public function byDepartment()
    {
        $tickets = Ticket::with('category')->latest()->get()->groupBy('department');
        return view('tickets.departments', compact('tickets'));
    }

    // 💾 Export Tickets (CSV, XLSX, PDF)
    public function export(Request $request, $type)
    {
        $query = Ticket::with(['category','assignee'])->latest();

        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('date_submitted', $request->month)
                  ->whereYear('date_submitted', $request->year);
        }

        $tickets = $query->get();

        if ($type === 'csv' || $type === 'xlsx') {
            return Excel::download(new TicketsExport($tickets), 'tickets.'.$type);
        } elseif ($type === 'pdf') {
            $pdf = Pdf::loadView('tickets.export_pdf', compact('tickets'))->setPaper('A4', 'landscape');
            return $pdf->download('tickets.pdf');
        }

        return back()->with('error', 'Invalid export type!');
    }

    // 🧾 Generate Job Order PDF
    public function jobOrderPdf(Ticket $ticket)
    {
        $ticket->load(['category', 'assignee']);
        return Pdf::loadView('tickets.job_order', compact('ticket'))
            ->setPaper('A4')
            ->download('JobOrder-' . $ticket->ticket_number . '.pdf');
    }
}
