<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Log an action.
     *
     * @param string $action
     * @param mixed $subjectModel
     * @param string|null $description
     */
    public static function log(string $action, $subjectModel, ?string $description = null)
    {
        $userId = Auth::id() ?? 1; // Default to admin if no auth

        ActivityLog::create([
            'user_id' => $userId,
            'action' => $action,
            'subject_type' => get_class($subjectModel),
            'subject_id' => $subjectModel->id ?? null,
            'description' => $description,
        ]);
    }
}
