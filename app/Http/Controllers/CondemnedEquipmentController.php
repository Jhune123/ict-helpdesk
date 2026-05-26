<?php

namespace App\Http\Controllers;

use App\Models\CondemnedEquipment;
use App\Models\Department;
use App\Models\Category;
use App\Models\User; // Added User model import
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
        
        $departments = Department::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        
        // Fetch IT personnel matching your ticket system. 
        $it_personnel = User::orderBy('name', 'asc')->get();
        
        return view('condemned.create', compact('departments', 'categories', 'it_personnel'));
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

        unset($validated['attachment']);

        CondemnedEquipment::create($validated);

        return redirect()->route('condemned-equipment.index')
                         ->with('success', 'Condemned equipment archived. Ticket: ' . $validated['ticket_number']);
    }

    public function show($id)
    {
        $condemnedEquipment = CondemnedEquipment::findOrFail($id);
        return view('condemned.show', compact('condemnedEquipment'));
    }

    public function edit($id)
    {
        $this->authorizeAdminOrStaff();
        $condemnedEquipment = CondemnedEquipment::findOrFail($id);
        
        $departments = Department::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();

        // Fetch IT personnel matching your ticket system.
        $it_personnel = User::orderBy('name', 'asc')->get();

        return view('condemned.edit', compact('condemnedEquipment', 'departments', 'categories', 'it_personnel'));
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

        unset($validated['attachment']);

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

    /**
     * Preview single certification PDF inline within the browser tab.
     */
    public function downloadCertificate($id)
    {
        $condemnedEquipment = CondemnedEquipment::findOrFail($id);
        $equipments = collect([$condemnedEquipment]);

        $ksuLogoPath = public_path('image/KSU-logo.png');
        $bpLogoPath = public_path('image/Bagong-Pilipinas.png');

        $ksuLogoBase64 = file_exists($ksuLogoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($ksuLogoPath)) : '';
        $bpLogoBase64 = file_exists($bpLogoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($bpLogoPath)) : '';

        $pdf = Pdf::loadView('condemned.pdf', compact('equipments', 'condemnedEquipment', 'ksuLogoBase64', 'bpLogoBase64'))
                  ->setPaper('a4', 'portrait');

        // Changed from ->download() to ->stream() to allow inline preview before printing
        return $pdf->stream('condemned_certification_' . $condemnedEquipment->ticket_number . '.pdf');
    }

    public function exportPdf()
    {
        $equipments = CondemnedEquipment::all();
        $condemnedEquipment = $equipments->first();

        if (!$condemnedEquipment) {
            $condemnedEquipment = new CondemnedEquipment([
                'ticket_number' => 'COND-2026-00000',
                'equipment_type' => 'N/A',
                'brand_model' => '',
                'serial_no' => 'N/A',
                'department' => 'N/A',
                'client_name' => 'N/A',
                'description' => 'No archived records found.',
                'it_personnel' => 'System Staff',
            ]);
        }

        $ksuLogoPath = public_path('image/KSU-logo.png');
        $bpLogoPath = public_path('image/Bagong-Pilipinas.png');

        $ksuLogoBase64 = file_exists($ksuLogoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($ksuLogoPath)) : '';
        $bpLogoBase64 = file_exists($bpLogoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($bpLogoPath)) : '';

        $pdf = Pdf::loadView('condemned.pdf', compact('equipments', 'condemnedEquipment', 'ksuLogoBase64', 'bpLogoBase64'))
                  ->setPaper('a4', 'portrait');

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