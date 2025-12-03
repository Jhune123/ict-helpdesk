<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // PDF support

class AssetController extends Controller
{
    // Display all assets
    public function index()
    {
        $assets = Asset::latest()->paginate(10);
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
        ]);

        Asset::create($validated);

        return redirect()->route('assets.index')->with('success', 'Asset added successfully.');
    }

    // View single asset
    public function show(Asset $asset)
    {
        return view('assets.show', compact('asset'));
    }

    // Edit form
    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    // Update record
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

    /**
     * Export all assets to PDF (A4 Landscape)
     */
    public function exportPdf()
{
    $assets = Asset::all(); // ✅ Fetch all rows

    $pdf = Pdf::loadView('assets.pdf', compact('assets'))
              ->setPaper('A4', 'landscape'); // ✅ Landscape

    $fileName = 'ICTO_Assets_'.now()->format('Ymd_His').'.pdf';
    return $pdf->download($fileName);
}
}
