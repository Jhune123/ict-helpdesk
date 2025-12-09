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

        $lastTicket = Ticket::orderBy('id', 'desc')->first();
        $newNumber = $lastTicket && $lastTicket->ticket_number
            ? str_pad((int) substr($lastTicket->ticket_number, -3) + 1, 3, '0', STR_PAD_LEFT)
            : '001';

        $ticketNumber = "KSU-ICTO-TIC-" . $newNumber;

        $categoryId = $validated['category_id'] ?? null;
        if (!$categoryId && !empty($validated['category_manual'])) {
            $category = Category::firstOrCreate(['name' => $validated['category_manual']]);
            $categoryId = $category->id;
        }

        $departmentName = $validated['department'] ?? null;
        if (!empty($validated['department_manual'])) {
            $departmentName = $validated['department_manual'];
        }

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

        // Notify assigned IT if exists
        if (!empty($validated['assigned_to'])) {
            $it = User::find($validated['assigned_to']);
            if ($it) {
                $it->notify(new TicketAssignedNotification($ticket));
            }
        }

        return redirect()->route('tickets.index')
            ->with('success', "Ticket created successfully ✅ Ticket Number: $ticketNumber");
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

        $categoryId = $validated['category_id'] ?? null;
        if (!$categoryId && !empty($validated['category_manual'])) {
            $category = Category::firstOrCreate(['name' => $validated['category_manual']]);
            $categoryId = $category->id;
        }

        $departmentName = $validated['department'] ?? null;
        if (!empty($validated['department_manual'])) {
            $departmentName = $validated['department_manual'];
        }

        // Determine if assigned IT changed
        $assignedChanged = $ticket->assigned_to != ($validated['assigned_to'] ?? null);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'] ?? 'Normal',
            'category_id' => $categoryId,
            'department' => $departmentName,
            'assigned_to' => $validated['assigned_to'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
            'client_name' => $validated['client_name'],
        ];

        // Update status and finished date
        if (!empty($validated['status'])) {
            if ($ticket->status != $validated['status']) {
                $data['status'] = $validated['status'];
                $data['date_finished'] = $validated['status'] === 'Closed'
                    ? Carbon::now('Asia/Manila')
                    : null;
            }
        }

        $ticket->update($data);

        // Notify IT only if assignment changed
        if ($assignedChanged && !empty($validated['assigned_to'])) {
            $it = User::find($validated['assigned_to']);
            if ($it) {
                $it->notify(new TicketAssignedNotification($ticket));
            }
        }

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket updated successfully ✅');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket deleted successfully ❌');
    }

    public function mine()
    {
        $tickets = Ticket::with(['category'])
            ->where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tickets.mine', compact('tickets'));
    }

    public function byDepartment()
    {
        $tickets = Ticket::with(['category'])
            ->orderBy('department')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('department');

        return view('tickets.departments', compact('tickets'));
    }

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

    public function jobOrderPdf(Ticket $ticket)
    {
        $ticket->load(['category', 'assignee']);

        $pdf = Pdf::loadView('tickets.job_order', compact('ticket'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('JobOrder-' . $ticket->ticket_number . '.pdf');
    }
}
