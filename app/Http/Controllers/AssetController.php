<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class AssetController extends Controller
{
    /**
     * Display all assets
     */
    public function index(Request $request)
    {
        $query = Asset::latest();

        if ($request->has('search')) {
            $query->where('property_no', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $assets = $query->get();
        return view('assets.index', compact('assets'));
    }

    public function create()
    {
        return view('assets.create');
    }

    /**
     * Store new asset with Error Catching
     */
    public function store(Request $request)
    {
        $validated = $this->validateAsset($request);

        try {
            Asset::create($validated);
            return redirect()->route('assets.index')->with('success', 'Asset added successfully.');
        } catch (\Exception $e) {
            // This will log the error and show it to you on localhost
            Log::error('Asset Store Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    public function show(Asset $asset)
    {
        return view('assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    /**
     * Update asset with Error Catching
     */
    public function update(Request $request, Asset $asset)
    {
        $validated = $this->validateAsset($request);

        try {
            $asset->update($validated);
            return redirect()->route('assets.index')->with('success', 'Asset updated successfully.');
        } catch (\Exception $e) {
            Log::error('Asset Update Error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully.');
    }

    public function exportPdf()
    {
        $assets = Asset::all();
        $pdf = Pdf::loadView('assets.pdf', compact('assets'))->setPaper('A4', 'landscape');
        return $pdf->download('ICTO_Assets_'.now()->format('Ymd_His').'.pdf');
    }

    /**
     * Centralized Validation
     */
    protected function validateAsset(Request $request)
    {
        return $request->validate([
            'entity_name'           => 'nullable|string|max:255',
            'fund_cluster'          => 'nullable|string|max:255',
            'par_no'                => 'nullable|string|max:255',
            'quantity'              => 'nullable|integer|min:0',
            'unit'                  => 'nullable|string|max:50',
            'description'           => 'nullable|string',
            'property_no'           => 'nullable|string|max:255',
            'date_acquired'         => 'nullable|date',
            'amount'                => 'nullable|numeric',
            'purpose'               => 'nullable|string',
            'approved_for_issuance' => 'nullable|string|max:255',
            'received_from'         => 'nullable|string|max:255',
            'received_by'           => 'nullable|string|max:255',
            'date_counted'          => 'nullable|date',
            'unit_status'           => 'required|string|in:Active,Under Repair,Condemned,For Replacement,Not Found in the Station',
        ]);
    }
}