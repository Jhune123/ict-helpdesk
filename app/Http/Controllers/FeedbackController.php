<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Ticket;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display a list of all feedbacks (Admin / ICT Personnel)
     */
    public function index()
    {
        $feedbacks = Feedback::with('ticket')
            ->latest()
            ->paginate(10);

        return view('feedbacks.index', compact('feedbacks'));
    }

    /**
     * Show the feedback form for a completed ticket (Client view)
     * Route: tickets/{ticket}/feedback
     */
    public function create(Ticket $ticket)
    {
        // Allow feedback ONLY for closed/completed tickets
        if ($ticket->status !== 'Closed') {
            return redirect()
                ->route('tickets.show', $ticket)
                ->with('error', 'Feedback can only be submitted for completed tickets.');
        }

        return view('feedbacks.create', compact('ticket'));
    }

    /**
     * Store the submitted feedback
     */
    public function store(Request $request, Ticket $ticket)
    {
        // Safety check (in case someone bypasses UI)
        if ($ticket->status !== 'Closed') {
            return redirect()
                ->route('tickets.show', $ticket)
                ->with('error', 'Feedback can only be submitted for completed tickets.');
        }

        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'rating'      => 'required|integer|min:1|max:5',
            'comments'    => 'nullable|string',
        ]);

        Feedback::create([
            'ticket_id'   => $ticket->id,
            'client_name' => $validated['client_name'],
            'rating'      => $validated['rating'],
            'comments'    => $validated['comments'] ?? null,
        ]);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Thank you! Your feedback has been submitted.');
    }

    /**
     * Show a single feedback (Admin view)
     */
    public function show(Feedback $feedback)
    {
        $feedback->load('ticket');

        return view('feedbacks.show', compact('feedback'));
    }

    /**
     * Delete a feedback (Admin only)
     */
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return redirect()
            ->route('feedbacks.index')
            ->with('success', 'Feedback deleted successfully.');
    }
}
