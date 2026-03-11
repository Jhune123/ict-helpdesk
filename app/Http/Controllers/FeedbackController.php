<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Ticket;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * 📊 Display a list of all feedbacks (Admin / ICT Personnel)
     */
    public function index()
    {
        $feedbacks = Feedback::with('ticket')
            ->latest()
            ->paginate(10);

        return view('feedbacks.index', compact('feedbacks'));
    }

    /**
     * 📝 Show the feedback form
     * Handled by GET: /feedbacks/create/{ticket}
     */
    public function create(Ticket $ticket)
    {
        // ✅ Allow feedback for BOTH Closed and Condemned tickets
        $completedStatuses = ['Closed', 'Condemned'];
        
        if (!in_array($ticket->status, $completedStatuses)) {
            return redirect()
                ->route('tickets.show', $ticket->id)
                ->with('error', 'Feedback can only be submitted for completed or archived tickets.');
        }

        // ✅ Prevent duplicate feedback
        if ($ticket->feedback) {
            return redirect()
                ->route('tickets.show', $ticket->id)
                ->with('info', 'Feedback has already been submitted for this ticket.');
        }

        return view('feedbacks.create', compact('ticket'));
    }

    /**
     * 💾 Store the submitted feedback
     * Handled by POST: /feedbacks/store/{ticket}
     */
    public function store(Request $request, Ticket $ticket)
    {
        $completedStatuses = ['Closed', 'Condemned'];

        // Safety check for status and duplicates
        if (!in_array($ticket->status, $completedStatuses)) {
            return redirect()
                ->route('tickets.show', $ticket->id)
                ->with('error', 'Feedback can only be submitted for completed tickets.');
        }

        if ($ticket->feedback) {
            return redirect()
                ->route('tickets.show', $ticket->id)
                ->with('error', 'Feedback already exists.');
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

        // ✅ Redirect using $ticket->id to satisfy the tickets.show route parameter
        return redirect()
            ->route('tickets.show', $ticket->id)
            ->with('success', 'Thank you! Your feedback has been submitted successfully ✅');
    }

    /**
     * 👁 Show a single feedback (Admin view)
     */
    public function show(Feedback $feedback)
    {
        $feedback->load('ticket');
        return view('feedbacks.show', compact('feedback'));
    }

    /**
     * 🗑 Delete a feedback (Admin only)
     */
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return redirect()
            ->route('feedbacks.index')
            ->with('success', 'Feedback deleted successfully.');
    }
}