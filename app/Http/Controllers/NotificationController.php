<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Show all notifications
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    // Mark as read
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }
        return back();
    }

    // Mark as unread
    public function markAsUnread($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->update(['read_at' => null]);
        }
        return back();
    }
}
