<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AssetController extends Controller
{
    // Display all assets
    public function index()
    {
        // ✅ Fetch all assets for client-side DataTables paging
        $assets = Asset::latest()->get();
        return view('assets.index', compact('assets'));
    }

    // Show create form
    public function create()
    {
        return view('assets.create');
    }

    // Store new asset
    public function store(Request $request)
    {
        $validated = $request->validate([
            'entity_name' => 'nullable|string',
            'fund_cluster' => 'nullable|string',
            'par_no' => 'nullable|string',
            'quantity' => 'nullable|integer',
            'unit' => 'nullable|string',
            'description' => 'nullable|string',
            'property_no' => 'nullable|string',
            'date_acquired' => 'nullable|date',
            'amount' => 'nullable|numeric',
            'purpose' => 'nullable|string',
            'approved_for_issuance' => 'nullable|string',
            'received_from' => 'nullable|string',
            'received_by' => 'nullable|string',
            'date_counted' => 'nullable|date',
            'unit_status' => 'required|string|in:Active,Under Repair,Condemned,For Replacement,Not Found in the Station',
        ]);

        Asset::create($validated);

        return redirect()->route('assets.index')->with('success', 'Asset added successfully.');
    }

    // Show single asset
    public function show(Asset $asset)
    {
        return view('assets.show', compact('asset'));
    }

    // Edit form
    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    // Update asset
    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'entity_name' => 'nullable|string',
            'fund_cluster' => 'nullable|string',
            'par_no' => 'nullable|string',
            'quantity' => 'nullable|integer',
            'unit' => 'nullable|string',
            'description' => 'nullable|string',
            'property_no' => 'nullable|string',
            'date_acquired' => 'nullable|date',
            'amount' => 'nullable|numeric',
            'purpose' => 'nullable|string',
            'approved_for_issuance' => 'nullable|string',
            'received_from' => 'nullable|string',
            'received_by' => 'nullable|string',
            'date_counted' => 'nullable|date',
            'unit_status' => 'required|string|in:Active,Under Repair,Condemned,For Replacement,Not Found in the Station',
        ]);

        $asset->update($validated);

        return redirect()->route('assets.index')->with('success', 'Asset updated successfully.');
    }

    // Delete asset
    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully.');
    }

    // Export PDF
    public function exportPdf()
    {
        $assets = Asset::all();

        $pdf = Pdf::loadView('assets.pdf', compact('assets'))
                  ->setPaper('A4', 'landscape');

        return $pdf->download('ICTO_Assets_'.now()->format('Ymd_His').'.pdf');
    }
}