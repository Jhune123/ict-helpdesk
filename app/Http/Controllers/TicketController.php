<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\Department;
use App\Models\User;
use App\Models\CondemnedEquipment;
use App\Models\NetworkRequest; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Notifications\TicketAssignedNotification;
use App\Notifications\TicketStatusChanged; 
use App\Helpers\ActivityLogger;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketsExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * 📝 Ticket List (Active Workspace)
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['category', 'assignee', 'feedback'])
            ->whereIn('status', ['Open', 'In Progress'])
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

        return view('tickets.index', compact('tickets'));
    }

    /**
     * 🏷 My Tickets
     */
    public function mine(Request $request)
    {
        $userId = auth()->id();
        $query = Ticket::with(['category', 'assignee', 'feedback'])
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

        $tickets = $query->paginate(20)->withQueryString();
        return view('tickets.mine', compact('tickets'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        
        $it_personnel = User::role(['admin', 'it_staff'])
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('tickets.create', compact('categories', 'departments', 'it_personnel'));
    }

    /**
     * 💾 Store Ticket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'nullable|string|max:255',
            'description'     => 'nullable|string',
            'equipment_type'  => 'nullable|string|max:255',
            'brand_model'     => 'nullable|string|max:255',
            'serial_no'       => 'nullable|string|max:255',
            'priority'        => 'nullable|string|max:50',
            'category_id'     => 'nullable|integer',
            'category_manual' => 'nullable|string|max:255',
            'department'      => 'nullable|string|max:255',
            'assigned_to'     => 'nullable|integer',
            'client_name'     => 'nullable|string|max:255',
            'contact_number'  => 'nullable|string|max:255', 
            'remarks'         => 'nullable|string|max:500',
            'form_data'       => 'nullable|array',
            'meta'            => 'nullable|array', 
            'status'          => 'nullable|string', 
            'form_type'       => 'nullable|string',
            'network_office'              => 'nullable|string|max:255',
            'network_request_type'        => 'nullable|string|max:255',
            'network_request_type_others' => 'nullable|string|max:255',
            'network_location'            => 'nullable|string|max:255',
            'network_mac_address'         => 'nullable|string|max:255',
            'network_device'              => 'nullable|string|max:255',
            'network_device_others'       => 'nullable|string|max:255',
        ]);

        $last = Ticket::latest()->first();
        $nextNum = $last ? ((int) substr($last->ticket_number, -3) + 1) : 1;
        $ticketNumber = 'KSU-ICTO-TIC-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

        $categoryId = $validated['category_id'] ?? null;
        if (!$categoryId && !empty($validated['category_manual'])) {
            $categoryId = Category::firstOrCreate(['name' => $validated['category_manual']])->id;
        }

        // Auto-assign category based on form type if not set
        if ($request->input('form_type') === 'network_request' && !$categoryId) {
            $categoryId = Category::firstOrCreate(['name' => 'Network / Internet'])->id;
        } elseif ($request->input('form_type') === 'multimedia_request' && !$categoryId) {
            $categoryId = Category::firstOrCreate(['name' => 'Multimedia Services'])->id;
        } elseif ($request->input('form_type') === 'information_system_request' && !$categoryId) {
            $categoryId = Category::firstOrCreate(['name' => 'Information System'])->id;
        } elseif (in_array($request->input('form_type'), ['equipment_request', 'equipment_repair', 'equipment_borrow']) && !$categoryId) {
            $categoryId = Category::firstOrCreate(['name' => 'Equipment Repair'])->id;
        }

        $deptInput = $validated['department'] ?? null;
        $department = $deptInput ? Department::firstOrCreate(['name' => $deptInput])->name : null;

        $assignedTo = Auth::user()->hasAnyRole(['admin', 'it_staff']) ? ($validated['assigned_to'] ?? null) : null;

        $formDataToSave = $validated['form_data'] ?? [];
        if (!empty($validated['meta'])) {
            $formDataToSave = array_merge($formDataToSave, $validated['meta']);
        }

        // Inject original form type metadata
        if ($request->input('form_type') === 'multimedia_request') {
            $formDataToSave['original_form_type'] = 'KSU-ICTO-QF-03';
        } elseif ($request->input('form_type') === 'information_system_request') {
            $formDataToSave['original_form_type'] = 'KSU-ICTO-QF-02';
        } elseif (in_array($request->input('form_type'), ['equipment_request', 'equipment_repair'])) {
            $formDataToSave['original_form_type'] = 'KSU-ICTO-QF-01';
        } elseif ($request->input('form_type') === 'equipment_borrow') {
            $formDataToSave['original_form_type'] = 'KSU-ICTO-QF-09';
        }

        $ticket = Ticket::create([
            'ticket_number'   => $ticketNumber,
            'title'           => $validated['title'] ?? 'QMS Support Ticket',
            'description'     => $validated['description'] ?? 'N/A',
            'equipment_type'  => $validated['equipment_type'] ?? 'N/A',
            'brand_model'     => $validated['brand_model'] ?? 'N/A',
            'serial_no'       => $validated['serial_no'] ?? 'N/A',
            'priority'        => $validated['priority'] ?? 'Normal',
            'category_id'     => $categoryId,
            'department'      => $department,
            'assigned_to'     => $assignedTo,
            'status'          => $validated['status'] ?? 'Open',
            'remarks'         => $validated['remarks'] ?? null,
            'client_name'     => $validated['client_name'] ?? Auth::user()->name, 
            'contact_number'  => $validated['contact_number'] ?? null,
            'date_submitted'  => Carbon::now('Asia/Manila'),
            'created_by'      => Auth::id(),
            'form_data'       => !empty($formDataToSave) ? $formDataToSave : null, 
        ]);

        if ($request->input('form_type') === 'network_request') {
            NetworkRequest::create([
                'ticket_id'           => $ticket->id,
                'user_id'             => Auth::id(),
                'office'              => $validated['network_office'] ?? 'N/A',
                'contact_number'      => $validated['contact_number'] ?? 'N/A',
                'request_type'        => $validated['network_request_type'] ?? 'N/A',
                'request_type_others' => $validated['network_request_type_others'] ?? null,
                'location'            => $validated['network_location'] ?? null,
                'mac_address'         => $validated['network_mac_address'] ?? null,
                'device'              => $validated['network_device'] ?? 'N/A',
                'device_others'       => $validated['network_device_others'] ?? null,
                'remarks'             => $validated['remarks'] ?? null,
            ]);
        }

        ActivityLogger::log('created', $ticket, 'Created Ticket: "' . $ticket->title . '"');

        if ($assignedTo && ($it = User::find($assignedTo))) {
            try { $it->notify(new TicketAssignedNotification($ticket)); } catch (\Exception $e) {}
        }

        return redirect()->route('tickets.show', $ticket->id)
                         ->with('success', "Ticket created ✅ ({$ticketNumber})");
    }

    /**
     * 👁 Show Ticket
     */
    public function show(Ticket $ticket)
    {
        $ticket->load(['comments.user', 'attachments', 'assignee', 'category', 'networkRequest', 'feedback']);
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $categories = Category::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        
        $it_personnel = User::role(['admin', 'it_staff'])
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('tickets.edit', compact('ticket', 'categories', 'departments', 'it_personnel'));
    }

    /**
     * ✏ Update Ticket Logic
     */
    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'equipment_type'  => 'nullable|string|max:255',
            'brand_model'     => 'nullable|string|max:255',
            'serial_no'       => 'nullable|string|max:255',
            'priority'        => 'nullable|string|max:50',
            'category_id'     => 'nullable|integer',
            'category_manual' => 'nullable|string|max:255',
            'department'      => 'nullable|string|max:255',
            'assigned_to'     => 'nullable|integer',
            'client_name'     => 'required|string|max:255',
            'contact_number'  => 'nullable|string|max:255', 
            'remarks'         => 'nullable|string|max:500',
            'status'          => 'required|string',
            'form_data'       => 'nullable|array', 
            'meta'            => 'nullable|array', 
        ]);

        $categoryId = $validated['category_id'] ?? $ticket->category_id;
        if (!empty($validated['category_manual'])) {
            $categoryId = Category::firstOrCreate(['name' => $validated['category_manual']])->id;
        }

        if ($validated['status'] === 'Condemned' && $ticket->status !== 'Condemned') {
            try {
                $categoryName = Category::find($categoryId)->name ?? 'Uncategorized';
                CondemnedEquipment::create([
                    'item_name'      => $validated['title'],
                    'property_no'    => $ticket->form_data['property_no'] ?? 'PENDING', 
                    'ticket_number'  => $ticket->ticket_number,
                    'title'          => $validated['title'],
                    'description'    => $validated['description'],
                    'equipment_type' => $validated['equipment_type'],
                    'brand_model'    => $validated['brand_model'],
                    'serial_no'      => $validated['serial_no'],
                    'category'       => $categoryName,
                    'department'     => $validated['department'],
                    'it_personnel'   => Auth::user()->name,
                    'client_name'    => $validated['client_name'],
                    'priority'       => $validated['priority'],
                    'contact'        => $validated['contact_number'],
                    'status'         => 'Condemned',
                    'date_submitted' => $ticket->created_at,
                    'date_finished'  => now(),
                ]);
            } catch (\Exception $e) {
                Log::error("Condemn Transfer Failed: " . $e->getMessage());
            }
        }

        $oldStatus = $ticket->status;
        $dateFinished = in_array($validated['status'], ['Closed', 'Condemned']) ? Carbon::now('Asia/Manila') : $ticket->date_finished;

        $updatedFormData = $ticket->form_data ?? [];
        if (!empty($validated['form_data'])) {
            $updatedFormData = array_merge($updatedFormData, $validated['form_data']);
        }
        if (!empty($validated['meta'])) {
            $updatedFormData = array_merge($updatedFormData, $validated['meta']);
        }

        $ticket->update([
            'title'           => $validated['title'],
            'description'     => $validated['description'],
            'equipment_type'  => $validated['equipment_type'],
            'brand_model'     => $validated['brand_model'],
            'serial_no'       => $validated['serial_no'],
            'priority'        => $validated['priority'],
            'category_id'     => $categoryId,
            'department'      => $validated['department'],
            'assigned_to'     => $validated['assigned_to'],
            'client_name'     => $validated['client_name'],
            'contact_number'  => $validated['contact_number'],
            'remarks'         => $validated['remarks'],
            'status'          => $validated['status'],
            'date_finished'   => $dateFinished,
            'form_data'       => !empty($updatedFormData) ? $updatedFormData : null, 
        ]);

        ActivityLogger::log('updated', $ticket, "Updated Ticket: #{$ticket->ticket_number}");

        if ($oldStatus !== $validated['status']) {
            try { $ticket->notify(new TicketStatusChanged($validated['status'])); } catch (\Exception $e) {}
        }

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Ticket updated successfully ✅');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket deleted ❌');
    }

    /**
     * 📄 Generate specialized Job Order PDF based on Category
     */
    public function jobOrderPdf(Ticket $ticket)
    {
        $categoryName = $ticket->category ? $ticket->category->name : 'General';
        $categoryLower = strtolower($categoryName);

        // 🔥 DEFAULT: The generic Equipment Repair form is now used for ALL categories natively
        $view = 'tickets.equipment-repair'; 

        // 🛑 EXCEPTIONS: Only route to other specific formats for the 4 distinct categories
        if (Str::contains($categoryLower, 'information system')) {
            $view = 'tickets.print-is';
        } 
        elseif (Str::contains($categoryLower, 'multimedia')) {
            $view = 'tickets.print-multimedia';
        } 
        elseif (Str::contains($categoryLower, 'network') || Str::contains($categoryLower, 'internet')) {
            $view = 'tickets.print-network';
        }
        elseif (Str::contains($categoryLower, 'borrow')) {
            $view = 'tickets.borrower-form';
        }

        // Secondary check for metadata flag (Overrides just in case categories were manually changed)
        if (isset($ticket->form_data['original_form_type'])) {
            if ($ticket->form_data['original_form_type'] === 'KSU-ICTO-QF-03') {
                $view = 'tickets.print-multimedia'; 
            } elseif ($ticket->form_data['original_form_type'] === 'KSU-ICTO-QF-02') {
                $view = 'tickets.print-is'; 
            } elseif ($ticket->form_data['original_form_type'] === 'KSU-ICTO-QF-09') {
                $view = 'tickets.borrower-form'; 
            }
        }

        return Pdf::loadView($view, compact('ticket'))
            ->setPaper('A4')
            ->stream('JobOrder-'.$ticket->ticket_number.'.pdf');
    }

    public function exportPdf()
    {
        $tickets = Ticket::with(['category', 'assignee'])->latest()->get();
        return Pdf::loadView('tickets.export_pdf', compact('tickets'))->setPaper('A4', 'landscape')->download('Tickets.pdf');
    }
}