<?php

namespace App\Http\Controllers;

use App\Models\Queue;

class DisplayController extends Controller
{
    public function index()
    {
        return view('display', [
            'now' => now(),

            'window1' => Queue::servingInWindow(1)->first(),
            'window2' => Queue::servingInWindow(2)->first(),

            'upcoming' => Queue::waiting()->oldest()->first(),
        ]);
    }
}
