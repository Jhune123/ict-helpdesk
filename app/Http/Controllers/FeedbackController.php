<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // 🌟 REQUIRED FOR PDF DOWNLOAD

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

        // ✅ Updated Validation to match ALL KSU CSM Form Data
        $validated = $request->validate([
            'client_name'       => 'nullable|string|max:255',
            'office_visited'    => 'required|string|max:255',
            'services_received' => 'required|string|max:255',
            'staff_assisted'    => 'required|string|max:255',
            'other_staff'       => 'nullable|string|max:255',
            'client_type'       => 'required|string',
            'agency_name'       => 'nullable|string',
            'sex'               => 'required|string',
            'age'               => 'required|integer|min:1',
            'cc1'               => 'required|integer',
            'cc2'               => 'required|integer',
            'cc3'               => 'required|integer',
            'sqd0'              => 'required|integer',
            'sqd1'              => 'required|integer',
            'sqd2'              => 'required|integer',
            'sqd3'              => 'required|integer',
            'sqd4'              => 'required|integer',
            'sqd5'              => 'required|integer',
            'sqd6'              => 'required|integer',
            'sqd7'              => 'required|integer',
            'sqd8'              => 'required|integer',
            'suggestions'       => 'nullable|string'
        ]);

        // ✅ Save the full payload
        Feedback::create([
            'ticket_id'         => $ticket->id,
            'user_id'           => auth()->id(),
            'client_name'       => $validated['client_name'],
            'office_visited'    => $validated['office_visited'],
            'services_received' => $validated['services_received'],
            'staff_assisted'    => $validated['staff_assisted'],
            'other_staff'       => $validated['other_staff'],
            'client_type'       => $validated['client_type'],
            'agency_name'       => $validated['agency_name'],
            'sex'               => $validated['sex'],
            'age'               => $validated['age'],
            'cc1'               => $validated['cc1'],
            'cc2'               => $validated['cc2'],
            'cc3'               => $validated['cc3'],
            'sqd0'              => $validated['sqd0'],
            'sqd1'              => $validated['sqd1'],
            'sqd2'              => $validated['sqd2'],
            'sqd3'              => $validated['sqd3'],
            'sqd4'              => $validated['sqd4'],
            'sqd5'              => $validated['sqd5'],
            'sqd6'              => $validated['sqd6'],
            'sqd7'              => $validated['sqd7'],
            'sqd8'              => $validated['sqd8'],
            'suggestions'       => $validated['suggestions'],
        ]);

        return redirect()
            ->route('tickets.show', $ticket->id)
            ->with('success', 'Thank you! Your feedback will help this office provide better service ✅');
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
     * ✏️ Show the form for editing the specified feedback
     * Handled by GET: /feedbacks/{feedback}/edit
     */
    public function edit(Feedback $feedback)
    {
        $feedback->load('ticket');
        return view('feedbacks.edit', compact('feedback'));
    }

    /**
     * 🔄 Update the specified feedback in storage
     * Handled by PUT/PATCH: /feedbacks/{feedback}
     */
    public function update(Request $request, Feedback $feedback)
    {
        $validated = $request->validate([
            'client_name'       => 'nullable|string|max:255',
            'office_visited'    => 'required|string|max:255',
            'services_received' => 'required|string|max:255',
            'staff_assisted'    => 'required|string|max:255',
            'other_staff'       => 'nullable|string|max:255',
            'client_type'       => 'required|string',
            'agency_name'       => 'nullable|string',
            'sex'               => 'required|string',
            'age'               => 'required|integer|min:1',
            'cc1'               => 'required|integer',
            'cc2'               => 'required|integer',
            'cc3'               => 'required|integer',
            'sqd0'              => 'required|integer',
            'sqd1'              => 'required|integer',
            'sqd2'              => 'required|integer',
            'sqd3'              => 'required|integer',
            'sqd4'              => 'required|integer',
            'sqd5'              => 'required|integer',
            'sqd6'              => 'required|integer',
            'sqd7'              => 'required|integer',
            'sqd8'              => 'required|integer',
            'suggestions'       => 'nullable|string'
        ]);

        $feedback->update($validated);

        return redirect()
            ->route('feedbacks.index')
            ->with('success', 'Feedback updated successfully ✅');
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

    /**
     * 📄 Generate and download the official KSU CSM PDF
     */
    public function downloadPdf(Feedback $feedback)
    {
        $feedback->load('ticket');

        // Human-readable mappings for the Citizen's Charter responses
        $cc1_choices = [
            1 => "I know what a CC is and I saw this office's CC.",
            2 => "I know what a CC is but I did NOT see this office's CC.",
            3 => "I learned of the CC only when I saw this office's CC.",
            4 => "I do not know what a CC is and I did not see one in this office."
        ];

        $cc2_choices = [
            1 => "Easy to see", 2 => "Somewhat easy to see", 3 => "Difficult to see", 
            4 => "Not visible at all", 5 => "N/A"
        ];

        $cc3_choices = [
            1 => "Helped very much", 2 => "Somewhat helped", 3 => "Did not help", 4 => "N/A"
        ];

        $sqd_choices = [
            5 => "Strongly Agree", 4 => "Agree", 3 => "Neither", 
            2 => "Disagree", 1 => "Strongly Disagree", 0 => "N/A"
        ];

        // Share data with the dedicated print view
        $pdf = Pdf::loadView('feedbacks.pdf', compact('feedback', 'cc1_choices', 'cc2_choices', 'cc3_choices', 'sqd_choices'));
        
        // Returns the downloadable file stream
        return $pdf->download('KSU-CSM-Feedback-'.$feedback->ticket->ticket_number.'.pdf');
    }
}