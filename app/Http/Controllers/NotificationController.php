<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Show all notifications for the authenticated user.
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(10);
        
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->markAsRead();
        }
        
        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark a specific notification as unread.
     */
    public function markAsUnread($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->markAsUnread(); // Using Laravel's native method for consistency
        }
        
        return back()->with('success', 'Notification marked as unread.');
    }
}