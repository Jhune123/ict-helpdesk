<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail; // <--- Added for Direct Email Test
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
use App\Http\Controllers\CondemnedEquipmentController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\MaintenanceScheduleController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('auth.login');
});

/* PUBLIC LIVE TV */
Route::get('/mis-queue/live-tv', [QueueController::class, 'liveTV'])->name('queues.live-tv');
Route::get('/mis-queue/live-tv-data', [QueueController::class, 'liveTVData'])->name('queues.live-tv-data');

/*
|--------------------------------------------------------------------------
| DASHBOARD & ANALYTICS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    Route::get('/dashboard/analytics', [DashboardController::class, 'analytics'])
        ->name('dashboard.analytics');

    Route::get('/analytics', [AnalyticsController::class, 'index'])
        ->name('analytics.index');

    Route::get('/dashboard/assets-analytics', [AssetController::class, 'analytics'])
        ->name('assets.analytics');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /* PROFILE */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* MIS QUEUING */
    Route::prefix('mis-queue')->group(function () {
        Route::get('/', [QueueController::class, 'operator'])->name('queues.index');
        Route::get('/operator', [QueueController::class, 'operator'])->name('queues.operator');
        Route::post('/add', [QueueController::class, 'add'])->name('queues.add');
        Route::patch('/serve/{queue}', [QueueController::class, 'serve'])->name('queues.serve');
        Route::patch('/complete/{queue}', [QueueController::class, 'complete'])->name('queues.complete');
        Route::post('/clear', [QueueController::class, 'clear'])->name('queues.clear');

        Route::get('/pdf/detailed', [QueueController::class, 'pdfDetailed'])->name('queues.pdf.detailed');
        Route::get('/pdf/summary', [QueueController::class, 'pdfSummary'])->name('queues.pdf.summary');
    });

    /* TICKETS */
    Route::get('/tickets/mine', [TicketController::class, 'mine'])->name('tickets.mine');
    Route::get('/tickets/departments', [TicketController::class, 'byDepartment'])->name('tickets.departments');

    Route::prefix('tickets/export')->group(function () {
        Route::get('/pdf', [TicketController::class, 'exportPdf'])->name('tickets.export.pdf');
        Route::get('/excel', [TicketController::class, 'exportExcel'])->name('tickets.export.excel');
        Route::get('/csv', [TicketController::class, 'exportCsv'])->name('tickets.export.csv');
    });

    Route::get('/tickets/{ticket}/job-order', [TicketController::class, 'jobOrderPdf'])
        ->name('tickets.jobOrderPdf');

    Route::resource('tickets', TicketController::class);

    /* CATEGORIES & DEPARTMENTS */
    Route::resource('categories', CategoryController::class);

    Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
        Route::resource('departments', DepartmentController::class)
            ->except(['create', 'show', 'edit']);
    });

    /* TASKS & MEETINGS */
    Route::resource('tasks', TaskScheduleController::class);
    Route::get('/tasks/export/pdf', [TaskScheduleController::class, 'exportPdf'])->name('tasks.export.pdf');

    Route::get('/meetings/calendar', [MeetingController::class, 'calendar'])->name('meetings.calendar');
    Route::resource('meetings', MeetingController::class);

    /*
    |--------------------------------------------------------------------------
    | PREVENTIVE MAINTENANCE (PMS)
    |--------------------------------------------------------------------------
    */
    Route::get('maintenance', [MaintenanceScheduleController::class, 'index'])->name('maintenance.index');
    Route::get('maintenance/export/pdf', [MaintenanceScheduleController::class, 'exportPdf'])->name('maintenance.pdf');
    Route::get('maintenance/{id}/job-order', [MaintenanceScheduleController::class, 'downloadJobOrder'])->name('maintenance.job_order');

    Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
        Route::get('maintenance/create', [MaintenanceScheduleController::class, 'create'])->name('maintenance.create');
        Route::post('maintenance', [MaintenanceScheduleController::class, 'store'])->name('maintenance.store');
        
        // This is the critical route for the "Complete" button
        Route::post('maintenance/{id}/complete', [MaintenanceScheduleController::class, 'completeTask'])->name('maintenance.complete');
        
        Route::get('maintenance/{maintenance}/edit', [MaintenanceScheduleController::class, 'edit'])->name('maintenance.edit');
        Route::put('maintenance/{maintenance}', [MaintenanceScheduleController::class, 'update'])->name('maintenance.update');
        Route::delete('maintenance/{maintenance}', [MaintenanceScheduleController::class, 'destroy'])->name('maintenance.destroy');
    });

    Route::get('maintenance/{maintenance}', [MaintenanceScheduleController::class, 'show'])->name('maintenance.show');


    /*
    |--------------------------------------------------------------------------
    | ASSETS
    |--------------------------------------------------------------------------
    */
    // ✅ View-only routes accessible by all authenticated users (including clients)
    Route::get('/assets/export/pdf', [AssetController::class, 'exportPdf'])->name('assets.export.pdf');
    Route::get('/assets/print', [AssetController::class, 'print'])->name('assets.print');
    Route::resource('assets', AssetController::class)->only(['index', 'show']);

    // ✅ Modification routes restricted to admin & IT staff
    Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
        Route::resource('assets', AssetController::class)->except(['index', 'show']);
    });

    /*
    |--------------------------------------------------------------------------
    | CONDEMNED EQUIPMENT
    |--------------------------------------------------------------------------
    */
    Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
        Route::get('/condemned-equipment/export/pdf', [CondemnedEquipmentController::class, 'exportPdf'])->name('condemned-equipment.export.pdf');
        Route::get('/condemned-equipment/export/excel', [CondemnedEquipmentController::class, 'exportExcel'])->name('condemned-equipment.export.excel');
        Route::get('/condemned-equipment/export/csv', [CondemnedEquipmentController::class, 'exportCsv'])->name('condemned-equipment.export.csv');

        Route::get('/condemned-equipment/create', [CondemnedEquipmentController::class, 'create'])->name('condemned-equipment.create');
        Route::post('/condemned-equipment', [CondemnedEquipmentController::class, 'store'])->name('condemned-equipment.store');

        Route::get('/condemned-equipment/{condemnedEquipment}/edit', [CondemnedEquipmentController::class, 'edit'])->name('condemned-equipment.edit');
        Route::put('/condemned-equipment/{condemnedEquipment}', [CondemnedEquipmentController::class, 'update'])->name('condemned-equipment.update');
        Route::delete('/condemned-equipment/{condemnedEquipment}', [CondemnedEquipmentController::class, 'destroy'])->name('condemned-equipment.destroy');
    });

    Route::get('/condemned-equipment', [CondemnedEquipmentController::class, 'index'])->name('condemned-equipment.index');
    Route::get('/condemned-equipment/{condemnedEquipment}', [CondemnedEquipmentController::class, 'show'])->name('condemned-equipment.show');


    /*
    |--------------------------------------------------------------------------
    | COMMENTS, ATTACHMENTS, FEEDBACK, NOTIFICATIONS
    |--------------------------------------------------------------------------
    */
    Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])->name('tickets.comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::post('/tickets/{ticket}/attachments', [AttachmentController::class, 'store'])->name('attachments.store');
    Route::get('/attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');
    Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/{id}/unread', [NotificationController::class, 'markAsUnread'])->name('notifications.markAsUnread');

    Route::get('/feedbacks', [FeedbackController::class, 'index'])->name('feedbacks.index');
    Route::get('/tickets/{ticket}/feedback', [FeedbackController::class, 'create'])->name('feedbacks.create');
    Route::post('/tickets/{ticket}/feedback', [FeedbackController::class, 'store'])->name('feedbacks.store');
    Route::get('/feedbacks/{feedback}', [FeedbackController::class, 'show'])->name('feedbacks.show');
    Route::delete('/feedbacks/{feedback}', [FeedbackController::class, 'destroy'])->name('feedbacks.destroy');

    /* ACTIVITY LOGS */
    Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('/activity-logs/export/{type}', [ActivityLogController::class, 'export'])->name('activity-logs.export');
    });
});

/*
|--------------------------------------------------------------------------
| TEMPORARY TESTING ROUTES
|--------------------------------------------------------------------------
*/

// TEST 1: Direct Email Connection (Debug SMTP)
Route::get('/test-email-direct', function () {
    try {
        Mail::raw('This is a direct SMTP test from KSU ICT Helpdesk.', function ($msg) {
            $msg->to('doctor.rogeliojr@gmail.com') // <--- Change this to your target email if needed
                ->subject('KSU ICT Helpdesk - Direct SMTP Connection Test');
        });
        return '✅ SUCCESS: Direct Email Sent! Check your inbox (doctor.rogeliojr@gmail.com) and Spam folder.';
    } catch (\Exception $e) {
        return '❌ FAILED: ' . $e->getMessage();
    }
});

// TEST 2: Full Notification System (SMS + Email)
Route::get('/test-sms-ticket', function () {
    // 1. Get the latest ticket
    $ticket = \App\Models\Ticket::latest()->first();

    if (!$ticket) {
        return "No tickets found in the database to test with.";
    }

    // 2. Temporarily force a phone number for testing
    // Replace this with your actual mobile number to receive the test SMS
    $ticket->contact_number = '09171234567'; 

    // 3. Trigger the notification
    try {
        // This will now use YOUR TicketStatusChanged.php logic (Email + SMS)
        $ticket->notify(new \App\Notifications\TicketStatusChanged('COMPLETED'));
        return "✅ Notification Triggered! <br> 1. Check your Email Inbox. <br> 2. Check laravel.log for SMS status.";
    } catch (\Exception $e) {
        return "❌ Error: " . $e->getMessage();
    }
});

/* AUTH */
require __DIR__ . '/auth.php';