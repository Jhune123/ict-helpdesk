<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
    // This pulls from the 'tickets' table we just saw in your terminal
    $queues = \App\Models\Ticket::whereIn('status', ['Open', 'In Progress', 'Closed'])
        ->orderByRaw("FIELD(status, 'In Progress', 'Open', 'Closed') ASC")
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('queues.index', compact('queues')); 
}

    /**
     * Live TV Monitor
     */
    public function liveTV()
    {
        // Identify which ticket is 'In Progress' for each specific staff member
        $servingJhune  = Ticket::where('status', 'In Progress')->where('remarks', 'LIKE', '%Jhune%')->first();
        $servingReymar = Ticket::where('status', 'In Progress')->where('remarks', 'LIKE', '%Reymar%')->first();
        $servingBryan  = Ticket::where('status', 'In Progress')->where('remarks', 'LIKE', '%Bryan%')->first();
        $servingWalid  = Ticket::where('status', 'In Progress')->where('remarks', 'LIKE', '%Walid%')->first();

        // Footer: Show the next 5 oldest 'Open' tickets
        $nextQueues = Ticket::where('status', 'Open')
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get();

        return view('queues.live-tv', compact('servingJhune', 'servingReymar', 'servingBryan', 'servingWalid', 'nextQueues'));
    }

    /**
     * Action: Serve a ticket
     */
    public function serve(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        // Update status and tag the personnel in remarks for the Live TV Monitor
        $ticket->update([
            'status' => 'In Progress',
            'remarks' => 'Serving at Counter: ' . $request->counter,
            'updated_at' => Carbon::now('Asia/Manila'),
        ]);

        return redirect()->back()->with('success', "Ticket {$ticket->ticket_number} called to {$request->counter}");
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