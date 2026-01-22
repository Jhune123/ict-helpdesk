<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MeetingController extends Controller
{
    // 🗂 Meetings Table
    public function index()
    {
        $meetings = Meeting::with('itPersonnel')
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        return view('meetings.index', compact('meetings'));
    }

    // 📆 FullCalendar View
    public function calendar()
    {
        $meetings = Meeting::with('itPersonnel')->get();

        $events = $meetings->map(function ($meeting) {
            if (!$meeting->date || !$meeting->start_time) return null;

            $title = $meeting->title ?? 'No Title';

            // Append IT personnel names if assigned
            if ($meeting->itPersonnel->count() > 0) {
                $title .= ' (IT: ' . $meeting->itPersonnel->pluck('name')->join(', ') . ')';
            }

            return [
                'id'    => $meeting->id,
                'title' => $title,
                'start' => Carbon::parse($meeting->date . ' ' . $meeting->start_time)->toIso8601String(),
                'end'   => $meeting->end_time
                            ? Carbon::parse($meeting->date . ' ' . $meeting->end_time)->toIso8601String()
                            : null,
                'url'   => route('meetings.show', $meeting->id),
                'color' => Carbon::parse($meeting->date)->gte(Carbon::today()) ? '#16A34A' : '#DC2626',
            ];
        })->filter()->values()->toArray();

        return view('meetings.calendar', compact('events'));
    }

    // ➕ Create Meeting
    public function create()
    {
        $itPersonnels = User::role('it_staff')->orderBy('name')->get();
        return view('meetings.create', compact('itPersonnels'));
    }

    // 💾 Store Meeting
    public function store(Request $request)
    {
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
        $meeting->itPersonnel()->sync(array_filter((array) $request->input('it_personnels')));

        return redirect()->route('meetings.index')->with('success', 'Meeting created successfully ✅');
    }

    // 👁 Show Meeting
    public function show(Meeting $meeting)
    {
        $meeting->load('itPersonnel');
        return view('meetings.show', compact('meeting'));
    }

    // ✏ Edit Meeting
    public function edit(Meeting $meeting)
    {
        $itPersonnels = User::role('it_staff')->orderBy('name')->get();
        $meeting->load('itPersonnel');

        return view('meetings.edit', compact('meeting', 'itPersonnels'));
    }

    // 🔄 Update Meeting
    public function update(Request $request, Meeting $meeting)
    {
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
        $meeting->itPersonnel()->sync(array_filter((array) $request->input('it_personnels')));

        return redirect()->route('meetings.index')->with('success', 'Meeting updated successfully ✅');
    }

    // 🗑 Delete Meeting
    public function destroy(Meeting $meeting)
    {
        $meeting->delete();
        return redirect()->route('meetings.index')->with('success', 'Meeting deleted successfully ❌');
    }
}
