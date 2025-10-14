<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function index()
    {
        // Clients can view all meetings but not edit or delete
        $meetings = Meeting::with('itPersonnels')->latest()->paginate(10);

        return view('meetings.index', compact('meetings'));
    }

    public function create()
    {
        // Allow only admin, IT staff, and clients to create
        if (!auth()->user()->hasAnyRole(['admin', 'it_staff', 'client'])) {
            abort(403, 'Unauthorized action.');
        }

        // Load only IT staff for selection
        $itPersonnels = User::where('role', 'it_staff')
            ->orderBy('name')
            ->distinct('id')
            ->get();

        return view('meetings.create', compact('itPersonnels'));
    }

    public function store(Request $request)
    {
        // Only admin, IT staff, or client can store
        if (!auth()->user()->hasAnyRole(['admin', 'it_staff', 'client'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'date'         => 'required|date',
            'start_time'   => 'required',
            'end_time'     => 'required|after:start_time',
            'location'     => 'required|string|max:255',
            'facilitator'  => 'nullable|string|max:255',
            'participants' => 'nullable|string',
            'remarks'      => 'nullable|string',
        ]);

        $meeting = Meeting::create($validated);

        $selected = $request->input('it_personnels') ?? [];
        $selected = is_array($selected) ? array_filter($selected) : [];

        if (!empty($selected)) {
            $meeting->itPersonnels()->sync($selected);
        }

        return redirect()->route('meetings.index')->with('success', 'Meeting created successfully ✅');
    }

    public function show(Meeting $meeting)
    {
        $meeting->load('itPersonnels');
        return view('meetings.show', compact('meeting'));
    }

    public function edit(Meeting $meeting)
    {
        // Only admin and IT staff can edit
        if (!auth()->user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized action.');
        }

        $itPersonnels = User::where('role', 'it_staff')
            ->orderBy('name')
            ->distinct('id')
            ->get();

        $meeting->load('itPersonnels');
        return view('meetings.edit', compact('meeting', 'itPersonnels'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        // Only admin and IT staff can update
        if (!auth()->user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'date'         => 'required|date',
            'start_time'   => 'required',
            'end_time'     => 'required|after:start_time',
            'location'     => 'required|string|max:255',
            'facilitator'  => 'nullable|string|max:255',
            'participants' => 'nullable|string',
            'remarks'      => 'nullable|string',
        ]);

        $meeting->update($validated);

        $selected = $request->input('it_personnels') ?? [];
        $selected = is_array($selected) ? array_filter($selected) : [];

        $meeting->itPersonnels()->sync($selected);

        return redirect()->route('meetings.index')->with('success', 'Meeting updated successfully ✅');
    }

    public function destroy(Meeting $meeting)
    {
        // Only admin and IT staff can delete
        if (!auth()->user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized action.');
        }

        $meeting->delete();

        return redirect()->route('meetings.index')->with('success', 'Meeting deleted successfully ❌');
    }
}
