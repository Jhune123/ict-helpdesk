<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketChatMessageNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DashboardChatController extends Controller
{
    public function index(Request $request)
    {
        $client = $request->user();

        abort_unless(
            ($client->hasRole('user') || $client->role === 'user')
                && ! $client->hasAnyRole(['admin', 'it_staff', 'ojt', 'supervisor', 'ojt_supervisor']),
            403
        );

        $chatTickets = Ticket::with(['comments.user', 'comments.recipient'])
            ->where('created_by', $client->id)
            ->latest()
            ->get();
        $chatPersonnel = User::role(['admin', 'it_staff'])->orderBy('name')->get(['id', 'name']);

        return view('dashboard.messages', compact('chatTickets', 'chatPersonnel'));
    }

    public function store(Request $request): RedirectResponse
    {
        $client = $request->user();

        abort_unless(
            ($client->hasRole('user') || $client->role === 'user')
                && ! $client->hasAnyRole(['admin', 'it_staff', 'ojt', 'supervisor', 'ojt_supervisor']),
            403
        );

        $validated = $request->validate([
            'ticket_id' => ['required', 'integer', 'exists:tickets,id'],
            'recipient_id' => ['required', 'integer', 'exists:users,id'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $ticket = Ticket::where('created_by', $client->id)->findOrFail($validated['ticket_id']);
        $recipient = User::role(['admin', 'it_staff'])->find($validated['recipient_id']);

        if (! $recipient) {
            throw ValidationException::withMessages([
                'recipient_id' => 'Choose a valid IT staff member or administrator.',
            ]);
        }

        Comment::create([
            'ticket_id' => $ticket->id,
            'user_id' => $client->id,
            'recipient_id' => $recipient->id,
            'message' => $validated['message'],
        ]);

        $recipient->notify(new TicketChatMessageNotification($ticket, $client, $validated['message']));

        return redirect()
            ->route('dashboard.chat.index')
            ->with('success', 'Your message was sent to '.$recipient->name.'.');
    }
}
