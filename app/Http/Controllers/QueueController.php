<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;

class QueueController extends Controller
{
    /* =========================
     * MAIN QUEUE INDEX
     * ========================= */
    public function index()
    {
        $queues = Queue::orderBy('queue_number')->get();

        return view('queues.index', compact('queues'));
    }

    /* =========================
     * OPERATOR DASHBOARD
     * ========================= */
    public function operator()
    {
        $waiting = Queue::where('status', 'waiting')
                        ->orderBy('queue_number')
                        ->get();

        $servingJhune = Queue::where('status', 'serving')
                             ->where('served_by', 'Jhune')
                             ->first();

        $servingReymar = Queue::where('status', 'serving')
                              ->where('served_by', 'Reymar')
                              ->first();

        return view('queues.operator', compact(
            'waiting',
            'servingJhune',
            'servingReymar'
        ));
    }

    /* =========================
     * ADD QUEUE NUMBER (MANUAL)
     * ========================= */
    public function add(Request $request)
    {
        $request->validate([
            'queue_number' => 'required|numeric|unique:queues,queue_number',
        ]);

        Queue::create([
            'queue_number' => $request->queue_number,
            'status'       => 'waiting',
        ]);

        return back()->with('success', 'Queue number added');
    }

    /* =========================
     * SERVE QUEUE
     * ========================= */
    public function serve(Request $request, Queue $queue)
    {
        $request->validate([
            'served_by' => 'required|in:Jhune,Reymar',
        ]);

        // Ensure only ONE serving per operator
        Queue::where('served_by', $request->served_by)
             ->where('status', 'serving')
             ->update([
                 'status'    => 'served',
                 'served_by' => null
             ]);

        $queue->update([
            'status'    => 'serving',
            'served_by' => $request->served_by,
        ]);

        return back();
    }

    /* =========================
     * COMPLETE SERVING
     * ========================= */
    public function complete(Queue $queue)
    {
        $queue->update([
            'status'    => 'served',
            'served_by' => null
        ]);

        return back();
    }

    /* =========================
     * LIVE TV DISPLAY (NO LOGIN)
     * ========================= */
    public function liveTV()
    {
        $servingJhune = Queue::where('status', 'serving')
                             ->where('served_by', 'Jhune')
                             ->first();

        $servingReymar = Queue::where('status', 'serving')
                              ->where('served_by', 'Reymar')
                              ->first();

        $nextQueue = Queue::where('status', 'waiting')
                          ->orderBy('queue_number')
                          ->first();

        return view('queues.live-tv', compact(
            'servingJhune',
            'servingReymar',
            'nextQueue'
        ));
    }
}
