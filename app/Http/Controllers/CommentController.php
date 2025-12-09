<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketNotificationMail;

class CommentController extends Controller
{
    /**
     * 💬 Store a new comment and send email notifications
     */
    public function store(Request $request, Ticket $ticket)
    {
        // ✅ Validate input
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // ✅ Create the comment
        $comment = Comment::create([
            'ticket_id' => $ticket->id,
            'user_id'   => Auth::id(),
            'message'   => $validated['message'],
        ]);

        // ✅ Email notification setup
        try {
            $creatorEmail  = $ticket->user->email ?? null;
            $assignedEmail = $ticket->assigned_to_user->email ?? null;

            $subject = 'New Comment on Ticket #' . $ticket->id;
            $messageBody = "A new comment was added to the ticket titled '{$ticket->title}'.\n\nComment:\n{$validated['message']}\n\nYou can view the ticket here:\n" . route('tickets.show', $ticket->id);

            // Send to ticket creator
            if ($creatorEmail) {
                Mail::to($creatorEmail)->send(new TicketNotificationMail($ticket, $subject, $messageBody));
            }

            // Send to assigned IT personnel if different
            if ($assignedEmail && $assignedEmail !== $creatorEmail) {
                Mail::to($assignedEmail)->send(new TicketNotificationMail($ticket, $subject, $messageBody));
            }
        } catch (\Exception $e) {
            \Log::error('Email notification failed: ' . $e->getMessage());
        }

        return redirect()
            ->route('tickets.show', $ticket->id)
            ->with('success', '💬 Comment added successfully! Email notification sent.');
    }

    /**
     * 🗑 Delete a comment
     */
    public function destroy(Comment $comment)
    {
        if (Auth::id() === $comment->user_id || Auth::user()->hasRole('admin')) {
            $comment->delete();
            return back()->with('success', '🗑 Comment deleted successfully!');
        }

        return back()->with('error', '⚠️ Unauthorized action!');
    }
}
