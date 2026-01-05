<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log($action, $subject, $description = null)
    {
        ActivityLog::create([
            'user_id'      => Auth::id(),
            'action'       => $action,
            'subject_type' => get_class($subject),
            'subject_id'   => $subject->id ?? null,
            'description'  => $description,
        ]);
    }
}
