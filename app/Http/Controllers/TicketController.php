<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\Department;
use App\Models\User;
use App\Models\CondemnedEquipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Notifications\TicketAssignedNotification;
use App\Notifications\TicketStatusChanged; 
use App\Helpers\ActivityLogger;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketsExport;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    /**
     * 📝 Ticket List (Pagination + Search restricted to specific fields)
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['category', 'assignee'])->orderBy('created_at', 'desc');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('serial_no', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhereHas('category', function($catQuery) use ($search) {
                      $catQuery->where('name', 'like', "%{$search}%");
                  });
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

    /**
     * 🏷 My Tickets (Search restricted to specific fields)
     */
    public function mine(Request $request)
    {
        $userId = auth()->id();
        $query = Ticket::with(['category', 'assignee'])
            ->where(function($q) use ($userId) {
                $q->where('created_by', $userId)
                  ->orWhere('assigned_to', $userId);
            })
            ->orderBy('created_at', 'desc');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('serial_no', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhereHas('category', function($catQuery) use ($search) {
                      $catQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('month')) {
            $query->whereMonth('date_submitted', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('date_submitted', $request->year);
        }

        $tickets = $query->paginate(20)->withQueryString();

        return view('tickets.mine', compact('tickets'));
    }

    /**
     * ➕ Create Ticket Form
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $it_personnel = User::role('it_staff')->select('id','name')->orderBy('name')->get();

        return view('tickets.create', compact('categories','departments','it_personnel'));
    }

    /**
     * 💾 Store Ticket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'equipment_type'  => 'required|string|max:255',
            'brand_model'     => 'required|string|max:255',
            'serial_no'       => 'required|string|max:255',
            'priority'        => 'nullable|string|max:50',
            'category_id'     => 'nullable|integer',
            'category_manual' => 'nullable|string|max:255',
            'department'      => 'nullable|string|max:255',
            'assigned_to'     => 'nullable|integer',
            'client_name'     => 'required|string|max:255',
            'contact_number'  => 'nullable|string|max:255', 
            'remarks'         => 'nullable|string|max:500',
        ]);

        $last = Ticket::latest()->first();
        $next = $last
            ? str_pad((int) substr($last->ticket_number, -3) + 1, 3, '0', STR_PAD_LEFT)
            : '001';
        $ticketNumber = 'KSU-ICTO-TIC-' . $next;

        $categoryId = $validated['category_id'] ?? null;
        if (!$categoryId && !empty($validated['category_manual'])) {
            $categoryId = Category::firstOrCreate(['name' => $validated['category_manual']])->id;
        }

        $department = $validated['department']
            ? Department::firstOrCreate(['name' => $validated['department']])->name
            : null;

        $assignedTo = Auth::user()->hasRole(['admin', 'it_staff'])
            ? ($validated['assigned_to'] ?? null)
            : null;

        $ticket = Ticket::create([
            'ticket_number'   => $ticketNumber,
            'title'           => $validated['title'],
            'description'     => $validated['description'],
            'equipment_type'  => $validated['equipment_type'],
            'brand_model'     => $validated['brand_model'],
            'serial_no'       => $validated['serial_no'],
            'priority'        => $validated['priority'] ?? 'Normal',
            'category_id'     => $categoryId,
            'department'      => $department,
            'assigned_to'     => $assignedTo,
            'status'          => 'Open',
            'remarks'         => $validated['remarks'] ?? null,
            'client_name'     => $validated['client_name'],
            'contact_number'  => $validated['contact_number'] ?? null,
            'date_submitted'  => Carbon::now('Asia/Manila'),
            'created_by'      => Auth::id(),
        ]);

        ActivityLogger::log('created', $ticket, 'Created Ticket: "' . $ticket->title . '"');

        if ($assignedTo && ($it = User::find($assignedTo))) {
            try {
                $it->notify(new TicketAssignedNotification($ticket));
            } catch (\Exception $e) {
                Log::error("Assignment Email Failed: " . $e->getMessage());
            }
            ActivityLogger::log('assigned', $ticket, 'Assigned Ticket to ' . $it->name);
        }

        return redirect()->route('tickets.index')->with('success', "Ticket created successfully ✅ ({$ticketNumber})");
    }

    /**
     * 👁 View Ticket
     */
    public function show(Ticket $ticket)
    {
        $ticket->load(['comments.user', 'attachments', 'assignee', 'category']);
        return view('tickets.show', compact('ticket'));
    }

    /**
     * ✏ Edit Ticket Form
     */
    public function edit(Ticket $ticket)
    {
        $categories = Category::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $it_personnel = User::role('it_staff')->select('id','name')->orderBy('name')->get();

        return view('tickets.edit', compact('ticket','categories','departments','it_personnel'));
    }

    /**
     * ✏ Update Ticket
     */
    public function update(Request $request, Ticket $ticket)
    {
        Log::info("Update Ticket Request Hit for ID: " . $ticket->id);

        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'equipment_type'  => 'required|string|max:255',
            'brand_model'     => 'required|string|max:255',
            'serial_no'       => 'required|string|max:255',
            'priority'        => 'nullable|string|max:50',
            'category_id'     => 'nullable|integer',
            'category_manual' => 'nullable|string|max:255',
            'department'      => 'nullable|string|max:255',
            'assigned_to'     => 'nullable|integer',
            'client_name'     => 'required|string|max:255',
            'contact_number'  => 'nullable|string|max:255', 
            'remarks'         => 'nullable|string|max:500',
            'status'          => 'required|string',
        ]);

        if ($validated['status'] === 'Condemned') {
            try {
                $alreadyExists = CondemnedEquipment::where('description', 'like', "%{$ticket->ticket_number}%")->exists();
                if (!$alreadyExists) {
                    $categoryName = $ticket->category ? $ticket->category->name : 'Uncategorized';

                    CondemnedEquipment::create([
                        'item_name'       => $ticket->title,
                        'title'           => $ticket->title,
                        'description'     => "From Ticket #{$ticket->ticket_number}: " . $ticket->description,
                        'equipment_type'  => $ticket->equipment_type,
                        'brand_model'     => $ticket->brand_model,
                        'serial_no'       => $ticket->serial_no,
                        'category'        => $categoryName,
                        'department'      => $ticket->department,
                        'client_name'     => $ticket->client_name,
                        'priority'        => $ticket->priority,
                        'contact'         => $ticket->contact_number, 
                        'status'          => 'Condemned',
                        'property_no'     => 'PENDING', 
                        'it_personnel'    => Auth::user()->name, 
                        'date_submitted'  => $ticket->created_at,
                        'date_condemned'  => now(),
                    ]);
                    Log::info("✅ Successfully moved Ticket {$ticket->ticket_number} to Condemned table.");
                }
            } catch (\Exception $e) {
                Log::error("Condemn Transfer Failed: " . $e->getMessage());
            }
        }

        $oldAssignee = $ticket->assigned_to;
        $oldStatus   = $ticket->status;

        $categoryId = $validated['category_id'] ?? null;
        if (!$categoryId && !empty($validated['category_manual'])) {
            $categoryId = Category::firstOrCreate(['name' => $validated['category_manual']])->id;
        }

        $department = $validated['department']
            ? Department::firstOrCreate(['name' => $validated['department']])->name
            : null;

        $assignedTo = Auth::user()->hasRole(['admin', 'it_staff'])
            ? ($validated['assigned_to'] ?? null)
            : $ticket->assigned_to;

        $dateFinished = in_array($validated['status'], ['Closed', 'Condemned']) ? Carbon::now('Asia/Manila') : null;

        $ticket->update([
            'title'           => $validated['title'],
            'description'     => $validated['description'],
            'equipment_type'  => $validated['equipment_type'],
            'brand_model'     => $validated['brand_model'],
            'serial_no'       => $validated['serial_no'],
            'priority'        => $validated['priority'] ?? 'Normal',
            'category_id'     => $categoryId,
            'department'      => $department,
            'assigned_to'     => $assignedTo,
            'client_name'     => $validated['client_name'],
            'contact_number'  => $validated['contact_number'] ?? null,
            'remarks'         => $validated['remarks'] ?? null,
            'status'          => $validated['status'],
            'date_finished'   => $dateFinished,
        ]);

        ActivityLogger::log('updated', $ticket, 'Updated Ticket: "' . $ticket->title . '"');

        if ($oldStatus !== $validated['status']) {
            try {
                $ticket->notify(new TicketStatusChanged($validated['status']));
            } catch (\Exception $e) {
                Log::error("Status Change Notification Failed: " . $e->getMessage());
            }
        }

        if ($oldAssignee != $assignedTo && $assignedTo) {
            if ($it = User::find($assignedTo)) {
                try {
                    $it->notify(new TicketAssignedNotification($ticket));
                } catch (\Exception $e) {
                    Log::error("Assignment Notification Failed: " . $e->getMessage());
                }
            }
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully ✅');
    }

    /**
     * 🗑 Delete Ticket
     */
    public function destroy(Ticket $ticket)
    {
        ActivityLogger::log('deleted', $ticket, 'Deleted Ticket: "' . $ticket->title . '"');
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully ❌');
    }

    /**
     * 🧾 Job Order PDF
     */
    public function jobOrderPdf(Ticket $ticket)
    {
        $ticket->load(['category', 'assignee']);
        return Pdf::loadView('tickets.job_order', compact('ticket'))
            ->setPaper('A4')
            ->download('JobOrder-' . $ticket->ticket_number . '.pdf');
    }

    /**
     * 📄 Export PDF
     */
    public function exportPdf()
    {
        $tickets = Ticket::with(['category', 'assignee'])->orderBy('created_at', 'desc')->get();
        return Pdf::loadView('tickets.export_pdf', compact('tickets'))
            ->setPaper('A4', 'landscape')
            ->download('Tickets-' . now()->format('Y-m-d_H-i') . '.pdf');
    }

    /**
     * 📊 Export Excel
     */
    public function exportExcel()
    {
        $tickets = Ticket::with(['category', 'assignee'])->orderBy('created_at', 'desc')->get();
        return Excel::download(new TicketsExport($tickets), 'Tickets-' . now()->format('Y-m-d_H-i') . '.xlsx');
    }

    /**
     * 🗂 Export CSV
     */
    public function exportCsv()
    {
        $tickets = Ticket::with(['category', 'assignee'])->orderBy('created_at', 'desc')->get();
        return Excel::download(new TicketsExport($tickets), 'Tickets-' . now()->format('Y-m-d_H-i') . '.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * 🏢 Tickets by Department (Search restricted to specific fields)
     */
    public function byDepartment(Request $request)
    {
        $query = Ticket::with(['assignee', 'category'])->orderBy('department')->orderBy('created_at', 'desc');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('serial_no', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhereHas('category', function($catQuery) use ($search) {
                      $catQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $tickets = $query->get()->groupBy(function ($ticket) {
            return $ticket->department ?? 'Unspecified Department';
        });

        return view('tickets.departments', compact('tickets'));
    }
}