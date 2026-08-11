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
use Illuminate\Support\Facades\DB;
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
     * 📝 Ticket List (Dynamic: Handles Active Workspaces & Archives)
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['category', 'assignee', 'feedback'])
            ->orderBy('created_at', 'desc');

        // Context check: Toggle between live operational queue or static archives log
        if ($request->input('view') === 'archive') {
            $query->whereIn('status', ['Closed', 'Finished', 'closed', 'finished']);
        } else {
            $query->whereIn('status', ['Open', 'In Progress', 'open', 'in progress']);
        }

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
            'title'                       => 'nullable|string|max:255',
            'description'                 => 'nullable|string',
            'equipment_type'              => 'nullable|string|max:255',
            'brand_model'                 => 'nullable|string|max:255',
            'serial_no'                   => 'nullable|string|max:255',
            'priority'                    => 'nullable|string|max:50',
            'category_id'                 => 'nullable|integer',
            'category_manual'             => 'nullable|string|max:255',
            'department'                  => 'nullable|string|max:255',
            'assigned_to'                 => 'nullable|integer',
            'client_name'                 => 'nullable|string|max:255',
            'contact_number'              => 'nullable|string|max:255', 
            'remarks'                     => 'nullable|string|max:500',
            'form_data'                   => 'nullable|array',
            'meta'                        => 'nullable|array', 
            'status'                      => 'nullable|string', 
            'form_type'                   => 'nullable|string',
            'network_office'              => 'nullable|string|max:255',
            'network_request_type'        => 'nullable|string|max:255',
            'network_request_type_others' => 'nullable|string|max:255',
            'network_location'            => 'nullable|string|max:255',
            'network_mac_address'         => 'nullable|string|max:255',
            'network_device'              => 'nullable|string|max:255',
            'network_device_others'       => 'nullable|string|max:255',
        ]);

        // 🛡️ Robust Ticket Generation
        $nextNum = 1;
        $lastTicket = DB::table('tickets')->orderBy('id', 'desc')->first();
        
        if ($lastTicket && !empty($lastTicket->ticket_number)) {
            $lastNumString = substr($lastTicket->ticket_number, 13);
            if (is_numeric($lastNumString)) {
                $nextNum = (int) $lastNumString + 1;
            }
        }

        do {
            $ticketNumber = 'KSU-ICTO-TIC-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
            $exists = DB::table('tickets')->where('ticket_number', $ticketNumber)->exists();
            if ($exists) {
                $nextNum++;
            }
        } while ($exists);

        $categoryId = $validated['category_id'] ?? null;
        if (!$categoryId && !empty($validated['category_manual'])) {
            $categoryId = Category::firstOrCreate(['name' => $validated['category_manual']])->id;
        }

        $formTypeLower = strtolower($request->input('form_type') ?? '');

        if ($formTypeLower === 'network_request' && !$categoryId) {
            $categoryId = Category::firstOrCreate(['name' => 'Network / Internet'])->id;
        } elseif ($formTypeLower === 'multimedia_request' && !$categoryId) {
            $categoryId = Category::firstOrCreate(['name' => 'Multimedia Services'])->id;
        } elseif ($formTypeLower === 'information_system_request' && !$categoryId) {
            $categoryId = Category::firstOrCreate(['name' => 'Information System'])->id;
        } elseif (in_array($formTypeLower, ['equipment_request', 'equipment_repair', 'equipment_borrow']) && !$categoryId) {
            $categoryId = Category::firstOrCreate(['name' => 'Equipment Repair'])->id;
        } elseif (Str::contains($formTypeLower, 'incident') && !$categoryId) {
            $categoryId = Category::firstOrCreate(['name' => 'Incident Report'])->id; 
        }

        $deptInput = $validated['department'] ?? null;
        $department = $deptInput ? Department::firstOrCreate(['name' => $deptInput])->name : null;

        $assignedTo = Auth::user()->hasAnyRole(['admin', 'it_staff']) ? ($validated['assigned_to'] ?? null) : null;

        $formDataToSave = $validated['form_data'] ?? [];
        if (!empty($validated['meta'])) {
            $formDataToSave = array_merge($formDataToSave, $validated['meta']);
        }

        if ($formTypeLower === 'multimedia_request') {
            $formDataToSave['original_form_type'] = 'KSU-ICTO-QF-03';
        } elseif ($formTypeLower === 'information_system_request') {
            $formDataToSave['original_form_type'] = 'KSU-ICTO-QF-02';
        } elseif (in_array($formTypeLower, ['equipment_request', 'equipment_repair'])) {
            $formDataToSave['original_form_type'] = 'KSU-ICTO-QF-01';
        } elseif ($formTypeLower === 'equipment_borrow') {
            $formDataToSave['original_form_type'] = 'KSU-ICTO-QF-09';
        } elseif (Str::contains($formTypeLower, 'incident')) {
            $formDataToSave['original_form_type'] = 'KSU-ICTO-QF-06'; 
        }

        $statusMap = [
            'open'        => 'Open',
            'in progress' => 'In Progress',
            'closed'      => 'Closed',
            'finished'    => 'Finished',
            'condemned'   => 'Condemned'
        ];
        $targetStatus = $validated['status'] ?? 'Open';
        $targetStatus = $statusMap[strtolower($targetStatus)] ?? $targetStatus;

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
            'status'          => $targetStatus,
            'remarks'         => $validated['remarks'] ?? null,
            'client_name'     => $validated['client_name'] ?? Auth::user()->name, 
            'contact_number'  => $validated['contact_number'] ?? null,
            'date_submitted'  => Carbon::now('Asia/Manila'),
            'created_by'      => Auth::id(),
            'form_data'       => !empty($formDataToSave) ? $formDataToSave : null, 
        ]);

        if ($formTypeLower === 'network_request') {
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

        if (strtolower($targetStatus) === 'condemned') {
            try {
                $categoryName = Category::find($categoryId)->name ?? 'Uncategorized';
                CondemnedEquipment::create([
                    'item_name'      => $ticket->title,
                    'property_no'    => $ticket->form_data['property_no'] ?? 'PENDING', 
                    'ticket_number'  => $ticket->ticket_number,
                    'title'          => $ticket->title,
                    'description'    => $ticket->description,
                    'equipment_type' => $ticket->equipment_type,
                    'brand_model'    => $ticket->brand_model,
                    'serial_no'      => $ticket->serial_no,
                    'category'       => $categoryName,
                    'department'     => $ticket->department,
                    'it_personnel'   => Auth::user()->name ?? 'System',
                    'client_name'    => $ticket->client_name,
                    'priority'       => $ticket->priority,
                    'contact'        => $ticket->contact_number,
                    'status'         => 'Condemned',
                    'date_submitted' => $ticket->created_at,
                    'date_condemned' => now(),
                ]);
            } catch (\Exception $e) {
                Log::error("Condemn Transfer Failed during explicit ticket creation: " . $e->getMessage());
            }
        }

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

    /**
     * 🔒 Security: Only IT/Admin can access Edit
     */
    public function edit(Ticket $ticket)
    {
        if (!Auth::user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized. Clients cannot edit tickets.');
        }

        $categories = Category::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        
        $it_personnel = User::role(['admin', 'it_staff'])
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return view('tickets.edit', compact('ticket', 'categories', 'departments', 'it_personnel'));
    }

    /**
     * 🔒 Security: Only IT/Admin can Update
     */
    public function update(Request $request, Ticket $ticket)
    {
        if (!Auth::user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized.');
        }

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

        $oldStatus = $ticket->status;

        $statusMap = [
            'open'        => 'Open',
            'in progress' => 'In Progress',
            'closed'      => 'Closed',
            'finished'    => 'Finished',
            'condemned'   => 'Condemned'
        ];
        
        $newStatus = $statusMap[strtolower($validated['status'])] ?? $validated['status'];
        $validated['status'] = $newStatus;

        if (strtolower($newStatus) === 'condemned' && strtolower($oldStatus) !== 'condemned') {
            $categoryName = Category::find($categoryId)->name ?? 'Uncategorized';
            
            $ticketFormData = is_string($ticket->form_data) ? json_decode($ticket->form_data, true) : ($ticket->form_data ?? []);
            $validatedFormData = is_array($validated['form_data'] ?? null) ? $validated['form_data'] : [];
            $propertyNo = $ticketFormData['property_no'] ?? ($validatedFormData['property_no'] ?? 'PENDING');

            $condemnedFields = [
                'item_name'      => $validated['title'] ?? $ticket->title,
                'property_no'    => $propertyNo, 
                'ticket_number'  => $ticket->ticket_number,
                'title'          => $validated['title'] ?? $ticket->title,
                'description'    => $validated['description'] ?? $ticket->description,
                'equipment_type' => $validated['equipment_type'] ?? $ticket->equipment_type,
                'brand_model'    => $validated['brand_model'] ?? $ticket->brand_model,
                'serial_no'      => $validated['serial_no'] ?? $ticket->serial_no,
                'category'       => $categoryName,
                'department'     => $validated['department'] ?? $ticket->department,
                'it_personnel'   => Auth::user()->name,
                'client_name'    => $validated['client_name'] ?? $ticket->client_name,
                'priority'       => $validated['priority'] ?? $ticket->priority,
                'contact'        => $validated['contact_number'] ?? $ticket->contact_number,
                'status'         => 'Condemned',
                'date_submitted' => $ticket->created_at,
                'date_condemned' => now(),
            ];

            CondemnedEquipment::create($condemnedFields);
        }

        $dateFinished = in_array(strtolower($newStatus), ['closed', 'finished', 'condemned']) 
            ? Carbon::now('Asia/Manila') 
            : $ticket->date_finished;

        $updatedFormData = is_string($ticket->form_data) ? json_decode($ticket->form_data, true) : ($ticket->form_data ?? []);
        
        if (!empty($validated['form_data'])) {
            $updatedFormData = array_merge((array) $updatedFormData, $validated['form_data']);
        }
        if (!empty($validated['meta'])) {
            $updatedFormData = array_merge((array) $updatedFormData, $validated['meta']);
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
            'status'          => $newStatus,
            'date_finished'   => $dateFinished,
            'form_data'       => !empty($updatedFormData) ? $updatedFormData : null, 
        ]);

        ActivityLogger::log('updated', $ticket, "Updated Ticket: #{$ticket->ticket_number}");

        if ($oldStatus !== $newStatus) {
            try { $ticket->notify(new TicketStatusChanged($newStatus)); } catch (\Exception $e) {}
        }

        $newStatusLower = strtolower($newStatus);
        if (strtolower($oldStatus) === 'condemned' && $newStatusLower !== 'condemned') {
            CondemnedEquipment::where('ticket_number', $ticket->ticket_number)->delete();
        }

        if (in_array($newStatusLower, ['open', 'in progress'])) {
            return redirect()->route('tickets.index')
                             ->with('success', 'Ticket updated successfully and moved to Active Workspace queue ✅');
        }

        if (in_array($newStatusLower, ['closed', 'finished'])) {
            return redirect()->route('tickets.index', ['view' => 'archive'])
                             ->with('success', 'Ticket resolved successfully and moved into Archives Archive 🗄️');
        }

        if ($newStatusLower === 'condemned') {
            return redirect()->route('condemned-equipment.index')
                             ->with('success', 'Ticket successfully transferred into Condemned Log registries 📋');
        }

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Ticket updated successfully ✅');
    }

    /**
     * 🔒 Security: Only IT/Admin can Delete
     */
    public function destroy(Ticket $ticket)
    {
        if (!Auth::user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized.');
        }

        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket deleted ❌');
    }

    /**
     * 📄 Generate Job Order PDF
     */
    public function jobOrderPdf(Ticket $ticket)
    {
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $categoryName = $ticket->category ? $ticket->category->name : 'General';
        
        $categoryLower = strtolower($categoryName);
        $titleLower = strtolower($ticket->title ?? '');

        $view = 'tickets.equipment-repair'; 

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
        elseif (Str::contains($categoryLower, 'incident') || Str::contains($titleLower, 'incident')) {
            $view = 'tickets.incident-report.job-order'; 
        }

        $formData = is_string($ticket->form_data) ? json_decode($ticket->form_data, true) : $ticket->form_data;

        if (is_array($formData)) {
            if (isset($formData['original_form_type'])) {
                if ($formData['original_form_type'] === 'KSU-ICTO-QF-03') {
                    $view = 'tickets.print-multimedia'; 
                } elseif ($formData['original_form_type'] === 'KSU-ICTO-QF-02') {
                    $view = 'tickets.print-is'; 
                } elseif ($formData['original_form_type'] === 'KSU-ICTO-QF-09') {
                    $view = 'tickets.borrower-form'; 
                } elseif ($formData['original_form_type'] === 'KSU-ICTO-QF-06') {
                    $view = 'tickets.incident-report.job-order'; 
                }
            }
            
            $embeddedFormType = strtolower($formData['form_type'] ?? '');
            if (Str::contains($embeddedFormType, 'incident')) {
                $view = 'tickets.incident-report.job-order';
            }
        }

        return Pdf::loadView($view, compact('ticket'))
            ->setPaper('A4', 'portrait')
            ->stream('JobOrder-'.$ticket->ticket_number.'.pdf');
    }

    /**
     * 📄 Export ALL Tickets PDF (Fetches all created tickets regardless of status or archive state)
     */
    public function exportPdf(Request $request)
    {
        // Remove memory & execution timeout constraints for large datasets
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $query = Ticket::with(['category:id,name', 'assignee:id,name'])
            ->select([
                'id', 'ticket_number', 'title', 'description', 'equipment_type',
                'brand_model', 'serial_no', 'category_id', 'department',
                'assigned_to', 'client_name', 'priority', 'contact_number',
                'status', 'date_submitted', 'date_finished', 'created_at'
            ])
            ->orderBy('created_at', 'desc');

        // Apply Search Filter if present
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

        // Apply Month Filter if present
        if ($request->filled('month')) {
            $query->whereMonth('date_submitted', $request->month);
        }

        // Apply Year Filter if present
        if ($request->filled('year')) {
            $query->whereYear('date_submitted', $request->year);
        }

        $tickets = $query->get();

        // Chunking array prevents Dompdf Cellmap memory leak across large table trees
        $ticketChunks = $tickets->chunk(50);
        $totalCount = $tickets->count();

        $pdf = Pdf::loadView('tickets.export_pdf', compact('ticketChunks', 'totalCount'))
            ->setPaper('A4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'defaultFont'          => 'sans-serif',
                'dpi'                  => 96,
                'enable_php'           => false,
            ]);

        return $pdf->download('Tickets_Report.pdf');
    }

    /**
     * 📊 Export Tickets Excel
     */
    public function exportExcel(Request $request)
    {
        return Excel::download(new TicketsExport($request), 'tickets.xlsx');
    }

    /**
     * 📄 Export Tickets CSV
     */
    public function exportCsv(Request $request)
    {
        return Excel::download(new TicketsExport($request), 'tickets.csv');
    }
}