<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use PDF; // Make sure barryvdh/laravel-dompdf is installed

class QueueController extends Controller
{
    /* =========================
     * OPERATOR DASHBOARD
     * ========================= */
    public function operator()
    {
        $queues = Queue::orderBy('id', 'asc')->get();

        $waiting = $queues->where('status', 'waiting');
        $servingJhune = $queues->where('status', 'serving')->where('served_by', 'Jhune')->first();
        $servingReymar = $queues->where('status', 'serving')->where('served_by', 'Reymar')->first();

        // Check if new queue can be added (max 5)
        $canAddQueue = $queues->whereIn('status', ['waiting', 'serving'])->count() < 5;

        return view('queues.operator', compact(
            'queues',
            'waiting',
            'servingJhune',
            'servingReymar',
            'canAddQueue'
        ));
    }

    /* =========================
     * ADD QUEUE NUMBER
     * ========================= */
    public function add()
    {
        $queuesCount = Queue::whereIn('status', ['waiting', 'serving'])->count();
        if ($queuesCount >= 5) {
            return back()->with('error', 'Maximum 5 queues allowed.');
        }

        $lastQueue = Queue::orderBy('id', 'desc')->first();
        $nextNumber = $lastQueue ? (int) substr($lastQueue->queue_number, 4) + 1 : 1;

        $queueNumber = 'MIS-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        Queue::create([
            'queue_number' => $queueNumber,
            'status'       => 'waiting',
        ]);

        return back()->with('success', "Queue {$queueNumber} added");
    }

    /* =========================
     * SERVE QUEUE
     * ========================= */
    public function serve(Request $request, Queue $queue)
    {
        $request->validate([
            'counter' => 'required|in:Jhune,Reymar',
        ]);

        // Complete currently serving queue for this counter
        Queue::where('served_by', $request->counter)
             ->where('status', 'serving')
             ->update([
                 'status'    => 'served',
                 'served_by' => null
             ]);

        // Assign this queue
        $queue->update([
            'status'    => 'serving',
            'served_by' => $request->counter,
        ]);

        return back();
    }

    /* =========================
     * COMPLETE QUEUE
     * ========================= */
    public function complete(Queue $queue)
    {
        $queue->update([
            'status'    => 'served',
            'served_by' => null,
        ]);

        return back();
    }

    /* =========================
     * CLEAR / RESET QUEUES
     * ========================= */
    public function clear()
    {
        Queue::truncate();
        return back()->with('success', 'All queues have been cleared.');
    }

    /* =========================
     * LIVE TV
     * ========================= */
    public function liveTV()
    {
        $servingJhune = Queue::where('status', 'serving')
            ->where('served_by', 'Jhune')
            ->latest('updated_at')
            ->first();

        $servingReymar = Queue::where('status', 'serving')
            ->where('served_by', 'Reymar')
            ->latest('updated_at')
            ->first();

        $nextQueues = Queue::where('status', 'waiting')
            ->orderBy('id')
            ->take(3)
            ->get();

        return view('queues.live-tv', compact(
            'servingJhune',
            'servingReymar',
            'nextQueues'
        ));
    }

    /* =========================
     * PDF REPORT
     * ========================= */
    public function pdfReport()
    {
        $queues = Queue::orderBy('id', 'asc')->get();
        $timestamp = now('Asia/Manila')->format('F d, Y | h:i A');

        $pdf = PDF::loadView('queues.pdf', compact('queues', 'timestamp'))
                  ->setPaper('A4', 'portrait');

        return $pdf->download('ICTO-MIS_Queue_Report_' . now('Asia/Manila')->format('Ymd_His') . '.pdf');
    }
}
