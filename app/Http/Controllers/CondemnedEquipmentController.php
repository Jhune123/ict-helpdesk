<?php

namespace App\Http\Controllers;

use App\Models\CondemnedEquipment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\CondemnedEquipmentExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Added for file deletion

class CondemnedEquipmentController extends Controller
{
    // Helper to protect Edit/Delete actions
    private function authorizeAdminOrStaff()
    {
        // Checks if user has EITHER 'admin' OR 'it_staff' role
        if (!Auth::user()->hasRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized action. View only.');
        }
    }

    public function index(Request $request)
    {
        $query = CondemnedEquipment::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('property_no', 'like', "%{$search}%")
                  ->orWhere('item_name', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('serial_no', 'like', "%{$search}%");
            });
        }

        $equipments = $query->latest()->paginate(15);
        return view('condemned.index', compact('equipments'));
    }

    public function create()
    {
        $this->authorizeAdminOrStaff(); // Locked
        return view('condemned.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdminOrStaff(); // Locked

        // 1. Validation
        $validated = $request->validate([
            'property_no'    => 'required|string|max:255',
            'item_name'      => 'required|string|max:255',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'equipment_type' => 'required|string|max:255',
            'brand_model'    => 'nullable|string|max:255',
            'serial_no'      => 'nullable|string|max:255',
            'category'       => 'nullable|string|max:255',
            'department'     => 'nullable|string|max:255',
            'it_personnel'   => 'nullable|string|max:255',
            'client_name'    => 'nullable|string|max:255',
            'priority'       => 'required|in:Low,Medium,High,Critical',
            'contact'        => 'nullable|string|max:255',
            'status'         => 'required|in:Open,In Progress,Finished,Closed,Condemned',
            'date_submitted' => 'nullable|date',
            'date_condemned' => 'nullable|date',
            // File Validation
            'attachment'     => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', 
        ]);

        // 2. Generate Auto Number (COND-YYYY-XXXXX)
        $year = now()->format('Y');

        // Find the last ticket created specifically in this year
        $lastTicket = CondemnedEquipment::where('ticket_number', 'like', "COND-$year-%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastTicket) {
            // Extract the last 5 digits (e.g., from COND-2026-00045, get 45)
            $lastNumber = intval(substr($lastTicket->ticket_number, -5));
            $newNumber = $lastNumber + 1;
        } else {
            // Start at 1 if no records exist for this year
            $newNumber = 1;
        }

        // Pad with zeros to ensure 5 digits (e.g., 1 -> 00001)
        $sequence = str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        
        // Assign the formatted ticket number
        $validated['ticket_number'] = "COND-$year-$sequence";

        // 3. Handle File Upload
        if ($request->hasFile('attachment')) {
            $validated['attachment_path'] = $request->file('attachment')->store('condemned_proofs', 'public');
        }

        CondemnedEquipment::create($validated);

        return redirect()->route('condemned-equipment.index')
                         ->with('success', 'Condemned equipment added successfully. Ticket: ' . $validated['ticket_number']);
    }

    public function show($id)
    {
        // Visible to Everyone (including 'user')
        $condemnedEquipment = CondemnedEquipment::findOrFail($id);
        return view('condemned.show', compact('condemnedEquipment'));
    }

    public function edit($id)
    {
        $this->authorizeAdminOrStaff(); // Locked
        $condemnedEquipment = CondemnedEquipment::findOrFail($id);
        return view('condemned.edit', compact('condemnedEquipment'));
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAdminOrStaff(); // Locked
        $condemnedEquipment = CondemnedEquipment::findOrFail($id);

        $validated = $request->validate([
            'property_no'    => 'required|string|max:255',
            'item_name'      => 'required|string|max:255',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'equipment_type' => 'required|string|max:255',
            'brand_model'    => 'nullable|string|max:255',
            'serial_no'      => 'nullable|string|max:255',
            'category'       => 'nullable|string|max:255',
            'department'     => 'nullable|string|max:255',
            'it_personnel'   => 'nullable|string|max:255',
            'client_name'    => 'nullable|string|max:255',
            'priority'       => 'required|in:Low,Medium,High,Critical',
            'contact'        => 'nullable|string|max:255',
            'status'         => 'required|in:Open,In Progress,Finished,Closed,Condemned',
            'date_submitted' => 'nullable|date',
            'date_condemned' => 'nullable|date',
            // File Validation
            'attachment'     => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        // Handle File Update
        if ($request->hasFile('attachment')) {
            // Optional: Delete old file if exists to save space
            if ($condemnedEquipment->attachment_path) {
                Storage::disk('public')->delete($condemnedEquipment->attachment_path);
            }
            // Store new file
            $validated['attachment_path'] = $request->file('attachment')->store('condemned_proofs', 'public');
        }

        $condemnedEquipment->update($validated);

        return redirect()->route('condemned-equipment.index')
                         ->with('success', 'Record updated successfully.');
    }

    public function destroy($id)
    {
        $this->authorizeAdminOrStaff(); // Locked
        $condemnedEquipment = CondemnedEquipment::findOrFail($id);
        
        // Optional: Delete attached file when record is deleted
        if ($condemnedEquipment->attachment_path) {
            Storage::disk('public')->delete($condemnedEquipment->attachment_path);
        }

        $condemnedEquipment->delete();
        return redirect()->route('condemned-equipment.index')
                         ->with('success', 'Record deleted successfully.');
    }

    public function exportPdf()
    {
        $equipments = CondemnedEquipment::all();
        $pdf = Pdf::loadView('condemned.pdf', compact('equipments'))->setPaper('a4', 'landscape');
        return $pdf->download('condemned_equipment_report.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new CondemnedEquipmentExport, 'condemned_equipment.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new CondemnedEquipmentExport, 'condemned_equipment.csv');
    }
}