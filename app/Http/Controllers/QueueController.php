<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class QueueController extends Controller
{
    /**
     * If your route points to index()
     */
    public function index()
    {
        return $this->operator();
    }

    /**
     * Dashboard: The Operator View
     * This fixes the "Call to undefined method... operator()" error
     */
    public function operator()
    {
        $queues = Ticket::whereIn('status', ['Open', 'In Progress', 'Closed'])
            ->orderByRaw("CASE status WHEN 'In Progress' THEN 1 WHEN 'Open' THEN 2 WHEN 'Closed' THEN 3 ELSE 4 END")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $user = auth()->user();
        $canOperateQueue = $user->roles()
            ->whereIn('name', ['admin', 'it_staff'])
            ->exists()
            || in_array($user->role, ['admin', 'it_staff'], true);

        $itPersonnel = $this->itPersonnel();

        return view('queues.index', compact('queues', 'canOperateQueue', 'itPersonnel'));
    }

    /**
     * Live TV Monitor
     */
    public function liveTV()
    {
        $itPersonnel = $this->itPersonnel();
        $servingTickets = Ticket::query()
            ->where('status', 'In Progress')
            ->whereIn('remarks', $itPersonnel->map(fn ($person) => 'Serving at Counter: ' . $person->name))
            ->get()
            ->keyBy('remarks');
        $servingAnnouncements = $servingTickets->map(fn ($ticket, $remarks) => [
            'id' => $ticket->id,
            'ticketNumber' => $ticket->ticket_number,
            'counter' => str_replace('Serving at Counter: ', '', $remarks),
        ])->values();

        // Show every ticket still listed as unfinished in the queue dashboard.
        $nextQueues = Ticket::whereIn('status', ['Open', 'In Progress'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('queues.live-tv', compact('itPersonnel', 'servingTickets', 'servingAnnouncements', 'nextQueues'));
    }

    /**
     * Action: Serve a ticket
     */
    public function serve(Request $request, $id)
    {
        $validated = $request->validate([
            'counter' => ['required', 'string', Rule::in($this->itPersonnel()->pluck('name')->all())],
        ]);

        $ticket = Ticket::findOrFail($id);

        // Update status and tag the personnel in remarks for the Live TV Monitor
        $ticket->update([
            'status' => 'In Progress',
            'remarks' => 'Serving at Counter: ' . $validated['counter'],
            'updated_at' => Carbon::now('Asia/Manila'),
        ]);

        return redirect()->back()->with('success', "Ticket {$ticket->ticket_number} called to {$validated['counter']}");
    }

    private function itPersonnel(): Collection
    {
        return User::query()
            ->where(function ($query) {
                $query->whereHas('roles', fn ($roles) => $roles->where('name', 'it_staff'))
                    ->orWhere('role', 'it_staff');
            })
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * Action: Complete a ticket
     */
    public function complete($id)
    {
        $ticket = Ticket::findOrFail($id);

        $ticket->update([
            'status' => 'Closed',
            'date_finished' => Carbon::now('Asia/Manila'),
        ]);

        return redirect()->back()->with('success', 'Ticket resolved.');
    }
}
