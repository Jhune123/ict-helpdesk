<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\RoleMiddleware;

/* CONTROLLERS */
use App\Http\Controllers\TaskScheduleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\QueueController;

/*----------------------------------------------------------
| PUBLIC ROUTES
|----------------------------------------------------------*/

/* ROOT → LOGIN */
Route::get('/', function () {
return view('auth.login');
});

/* 🔴 PUBLIC LIVE TV (NO LOGIN – FOR STUDENTS) */
Route::get('/mis-queue/live-tv', [QueueController::class, 'liveTV'])
    ->name('queues.live-tv');

/*----------------------------------------------------------
| DASHBOARD
|----------------------------------------------------------*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*----------------------------------------------------------
| ANALYTICS
|----------------------------------------------------------*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard/analytics', [DashboardController::class, 'analytics'])
        ->name('dashboard.analytics');

    Route::get('/analytics', [AnalyticsController::class, 'index'])
        ->name('analytics.index');

    Route::get('/dashboard/assets-analytics', [AssetController::class, 'analytics'])
        ->name('assets.analytics');
});

/*----------------------------------------------------------
| AUTHENTICATED ROUTES
|----------------------------------------------------------*/
Route::middleware('auth')->group(function () {

    /* PROFILE */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*------------------------------------------------------
    | ICTO – MIS QUEUING SYSTEM ✅ FINAL
    |------------------------------------------------------*/
    Route::prefix('mis-queue')->group(function () {

        /* MAIN QUEUE DASHBOARD – FIX queues.index */
        Route::get('/', [QueueController::class, 'index'])
            ->name('queues.index');

        /* OPERATOR PANEL (Jhune / Reymar) */
        Route::get('/operator', [QueueController::class, 'operator'])
            ->name('queues.operator');

        /* ADD QUEUE NUMBER */
        Route::post('/add', [QueueController::class, 'add'])
            ->name('queues.add');

        /* SERVE QUEUE – PATCH */
        Route::patch('/serve/{queue}', [QueueController::class, 'serve'])
            ->name('queues.serve');

        /* COMPLETE SERVING – PATCH */
        Route::patch('/complete/{queue}', [QueueController::class, 'complete'])
            ->name('queues.complete');

        /* LIVE TV DISPLAY (PUBLIC) */
        Route::get('/live-tv', [QueueController::class, 'liveTV'])
            ->name('queues.live-tv');
    });

    /*------------------------------------------------------
    | TICKETS
    |------------------------------------------------------*/
    Route::get('/tickets/mine', [TicketController::class, 'mine'])->name('tickets.mine');
    Route::get('/tickets/departments', [TicketController::class, 'byDepartment'])->name('tickets.departments');
    Route::get('/tickets/export/{type}', [TicketController::class, 'export'])->name('tickets.export');
    Route::get('/tickets/{ticket}/job-order', [TicketController::class, 'jobOrderPdf'])->name('tickets.joborder.pdf');
    Route::resource('tickets', TicketController::class);

    /* CATEGORIES */
    Route::resource('categories', CategoryController::class);

    /* DEPARTMENTS (ADMIN / IT STAFF) */
    Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
        Route::resource('departments', DepartmentController::class)
            ->except(['create', 'show', 'edit']);
    });

    /* TASKS */
    Route::resource('tasks', TaskScheduleController::class);
    Route::get('/tasks/export/pdf', [TaskScheduleController::class, 'exportPdf'])
        ->name('tasks.export.pdf');

    /* MEETINGS */
    Route::get('/meetings/calendar', [MeetingController::class, 'calendar'])
        ->name('meetings.calendar');
    Route::resource('meetings', MeetingController::class);

    /* ASSETS (ADMIN / IT STAFF) */
    Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
        Route::resource('assets', AssetController::class);
        Route::get('/assets/export/pdf', [AssetController::class, 'exportPdf'])
            ->name('assets.export.pdf');
        Route::get('/assets/print', [AssetController::class, 'print'])
            ->name('assets.print');
    });

    /* COMMENTS */
    Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])
        ->name('tickets.comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');

    /* ATTACHMENTS */
    Route::post('/tickets/{ticket}/attachments', [AttachmentController::class, 'store'])
        ->name('attachments.store');
    Route::get('/attachments/{attachment}/download', [AttachmentController::class, 'download'])
        ->name('attachments.download');
    Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy'])
        ->name('attachments.destroy');

    /* NOTIFICATIONS */
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.markAsRead');
    Route::post('/notifications/{id}/unread', [NotificationController::class, 'markAsUnread'])
        ->name('notifications.markAsUnread');

    /* FEEDBACK */
    Route::get('/feedbacks', [FeedbackController::class, 'index'])
        ->name('feedbacks.index');
    Route::get('/tickets/{ticket}/feedback', [FeedbackController::class, 'create'])
        ->name('feedbacks.create');
    Route::post('/tickets/{ticket}/feedback', [FeedbackController::class, 'store'])
        ->name('feedbacks.store');
    Route::get('/feedbacks/{feedback}', [FeedbackController::class, 'show'])
        ->name('feedbacks.show');
    Route::delete('/feedbacks/{feedback}', [FeedbackController::class, 'destroy'])
        ->name('feedbacks.destroy');

    /* ACTIVITY LOGS (ADMIN / IT STAFF) */
    Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])
            ->name('activity-logs.index');
        Route::get('/activity-logs/export/{type}', [ActivityLogController::class, 'export'])
            ->name('activity-logs.export');
    });
});

/* AUTH ROUTES */
require __DIR__ . '/auth.php';
