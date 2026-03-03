<?php

namespace App\Http\Controllers;

use App\Models\CondemnedEquipment;
use App\Models\Department; // Added to fetch department lists
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\CondemnedEquipmentExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CondemnedEquipmentController extends Controller
{
    /**
     * Helper to protect Edit/Delete/Store actions.
     */
    private function authorizeAdminOrStaff()
    {
        if (!Auth::user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized action. You do not have permission to manage condemned equipment.');
        }
    }

    /**
     * Display a listing of the condemned equipment.
     */
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
                  ->orWhere('serial_no', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%");
            });
        }

        $equipments = $query->latest()->paginate(15);
        return view('condemned.index', compact('equipments'));
    }

    public function create()
    {
        $this->authorizeAdminOrStaff();
        
        // Fetch departments from database to match Ticket system
        $departments = Department::orderBy('name', 'asc')->pluck('name');
        
        return view('condemned.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdminOrStaff();

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
            'attachment'     => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', 
        ]);

        $year = now()->format('Y');
        $lastTicket = CondemnedEquipment::where('ticket_number', 'like', "COND-$year-%")
            ->orderBy('id', 'desc')
            ->first();

        $newNumber = $lastTicket ? (intval(substr($lastTicket->ticket_number, -5)) + 1) : 1;
        $sequence = str_pad($newNumber, 5, '0', STR_PAD_LEFT);
        $validated['ticket_number'] = "COND-$year-$sequence";

        if ($request->hasFile('attachment')) {
            $validated['attachment_path'] = $request->file('attachment')->store('condemned_proofs', 'public');
        }

        CondemnedEquipment::create($validated);

        return redirect()->route('condemned-equipment.index')
                         ->with('success', 'Condemned equipment archived. Ticket: ' . $validated['ticket_number']);
    }

    public function show($id)
    {
        $equipment = CondemnedEquipment::findOrFail($id);
        return view('condemned.show', compact('equipment'));
    }

    public function edit($id)
    {
        $this->authorizeAdminOrStaff();
        $equipment = CondemnedEquipment::findOrFail($id);
        $departments = Department::orderBy('name', 'asc')->pluck('name');

        return view('condemned.edit', compact('equipment', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAdminOrStaff();
        $equipment = CondemnedEquipment::findOrFail($id);

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
            'attachment'     => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        if ($request->hasFile('attachment')) {
            if ($equipment->attachment_path) {
                Storage::disk('public')->delete($equipment->attachment_path);
            }
            $validated['attachment_path'] = $request->file('attachment')->store('condemned_proofs', 'public');
        }

        $equipment->update($validated);

        return redirect()->route('condemned-equipment.index')
                         ->with('success', 'Record updated successfully.');
    }

    public function destroy($id)
    {
        $this->authorizeAdminOrStaff();
        $equipment = CondemnedEquipment::findOrFail($id);
        
        if ($equipment->attachment_path) {
            Storage::disk('public')->delete($equipment->attachment_path);
        }

        $equipment->delete();
        return redirect()->route('condemned-equipment.index')
                         ->with('success', 'Record deleted successfully.');
    }

    public function exportPdf()
    {
        $equipments = CondemnedEquipment::all();
        $pdf = Pdf::loadView('condemned.pdf', compact('equipments'))
                  ->setPaper('a4', 'landscape');
        return $pdf->download('condemned_equipment_report_' . now()->format('Ymd') . '.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new CondemnedEquipmentExport, 'condemned_equipment_' . now()->format('Ymd') . '.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new CondemnedEquipmentExport, 'condemned_equipment_' . now()->format('Ymd') . '.csv');
    }
}