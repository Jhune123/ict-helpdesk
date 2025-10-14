<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $request->validate([
            'file' => 'required|file|max:5120', // max 5MB
        ]);

        $file = $request->file('file');
        $path = $file->store('attachments', 'public');

        Attachment::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'filename' => $file->getClientOriginalName(),
            'filepath' => $path,
            'filesize' => number_format($file->getSize() / 1024, 2) . ' KB',
        ]);

        return back()->with('success', '📎 File uploaded successfully!');
    }

    public function download(Attachment $attachment)
    {
        return Storage::disk('public')->download($attachment->filepath, $attachment->filename);
    }

    public function destroy(Attachment $attachment)
    {
        if (Auth::id() === $attachment->user_id || Auth::user()->hasRole('admin')) {
            Storage::disk('public')->delete($attachment->filepath);
            $attachment->delete();
            return back()->with('success', '🗑 Attachment deleted successfully!');
        }

        return back()->with('error', '⚠️ Unauthorized action!');
    }
}
