<?php

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
use App\Http\Controllers\FeedbackController; // ✅ Added FeedbackController
use Illuminate\Support\Facades\Route;

/**
 * ------------------------------------
 *  PUBLIC ROUTES
 * ------------------------------------
 */
Route::get('/', function () {
    return view('welcome');
});

/**
 * DASHBOARD (Default)
 */
Route::get('/dashboard', function () {
    return view('dashboard');
})
->middleware(['auth', 'verified'])
->name('dashboard');

/**
 * DASHBOARD ANALYTICS
 */
Route::get('/dashboard/analytics', [DashboardController::class, 'analytics'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.analytics');

/**
 * TICKET ANALYTICS (NEW)
 */
Route::get('/analytics', [AnalyticsController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('analytics.index');

/**
 * ASSET ANALYTICS
 */
Route::get('/dashboard/assets-analytics', [AssetController::class, 'analytics'])
    ->middleware(['auth', 'verified'])
    ->name('assets.analytics');

/**
 * ------------------------------------
 *  AUTHENTICATED ROUTES
 * ------------------------------------
 */
Route::middleware('auth')->group(function () {

    /** USER PROFILE */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /** TICKET MANAGEMENT */
    Route::get('/tickets/mine', [TicketController::class, 'mine'])->name('tickets.mine');
    Route::get('/tickets/departments', [TicketController::class, 'byDepartment'])->name('tickets.departments');

    /** EXPORT TICKETS */
    Route::get('/tickets/export/{type}', [TicketController::class, 'export'])->name('tickets.export');

    /** JOB ORDER PDF */
    Route::get('/tickets/{ticket}/job-order', [TicketController::class, 'jobOrderPdf'])->name('tickets.joborder.pdf');

    /** FULL CRUD FOR TICKETS */
    Route::resource('tickets', TicketController::class);

    /** CATEGORIES, DEPARTMENTS, TASKS, MEETINGS */
    Route::resource('categories', CategoryController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('tasks', TaskScheduleController::class);
    Route::get('/tasks/export/pdf', [TaskScheduleController::class, 'exportPdf'])->name('tasks.export.pdf');
    Route::resource('meetings', MeetingController::class);

    /** ASSETS MODULE */
    Route::resource('assets', AssetController::class);
    Route::get('/assets/export/pdf', [AssetController::class, 'exportPdf'])->name('assets.export.pdf');
    Route::get('/assets/print', [AssetController::class, 'print'])->name('assets.print');

    /** COMMENTS */
    Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])->name('tickets.comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    /** ATTACHMENTS */
    Route::post('/tickets/{ticket}/attachments', [App\Http\Controllers\AttachmentController::class, 'store'])->name('attachments.store');
    Route::get('/attachments/{attachment}/download', [App\Http\Controllers\AttachmentController::class, 'download'])->name('attachments.download');
    Route::delete('/attachments/{attachment}', [App\Http\Controllers\AttachmentController::class, 'destroy'])->name('attachments.destroy');

    /** NOTIFICATIONS */
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/{id}/unread', [NotificationController::class, 'markAsUnread'])->name('notifications.markAsUnread');

    /** CLIENT FEEDBACK */
    Route::get('/feedbacks', [FeedbackController::class, 'index'])->name('feedbacks.index'); // View all feedbacks (Admin)
    
    // Feedback form for a closed ticket (Client)
    Route::get('/tickets/{ticket}/feedback', [FeedbackController::class, 'create'])->name('feedbacks.create');

    // Submit feedback for a ticket
    Route::post('/tickets/{ticket}/feedback', [FeedbackController::class, 'store'])->name('feedbacks.store');

    // Optional: Show single feedback (Admin)
    Route::get('/feedbacks/{feedback}', [FeedbackController::class, 'show'])->name('feedbacks.show');

    // Optional: Delete feedback (Admin)
    Route::delete('/feedbacks/{feedback}', [FeedbackController::class, 'destroy'])->name('feedbacks.destroy');
});

require __DIR__ . '/auth.php';
