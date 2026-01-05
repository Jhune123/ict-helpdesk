<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\RoleMiddleware;

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

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})
->middleware(['auth', 'verified'])
->name('dashboard');

/*
|--------------------------------------------------------------------------
| ANALYTICS
|--------------------------------------------------------------------------
*/
Route::get('/dashboard/analytics', [DashboardController::class, 'analytics'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.analytics');

Route::get('/analytics', [AnalyticsController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('analytics.index');

Route::get('/dashboard/assets-analytics', [AssetController::class, 'analytics'])
    ->middleware(['auth', 'verified'])
    ->name('assets.analytics');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // User Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tickets
    Route::get('/tickets/mine', [TicketController::class, 'mine'])->name('tickets.mine');
    Route::get('/tickets/departments', [TicketController::class, 'byDepartment'])->name('tickets.departments');
    Route::get('/tickets/export/{type}', [TicketController::class, 'export'])->name('tickets.export');
    Route::get('/tickets/{ticket}/job-order', [TicketController::class, 'jobOrderPdf'])->name('tickets.joborder.pdf');
    Route::resource('tickets', TicketController::class);

    // Categories, Departments, Tasks, Meetings
    Route::resource('categories', CategoryController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('tasks', TaskScheduleController::class);
    Route::get('/tasks/export/pdf', [TaskScheduleController::class, 'exportPdf'])->name('tasks.export.pdf');
    Route::resource('meetings', MeetingController::class);

    // Assets (Admin / IT Staff only)
    Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
        Route::resource('assets', AssetController::class);
        Route::get('/assets/export/pdf', [AssetController::class, 'exportPdf'])->name('assets.export.pdf');
        Route::get('/assets/print', [AssetController::class, 'print'])->name('assets.print');
    });

    // Comments
    Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])->name('tickets.comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Attachments
    Route::post('/tickets/{ticket}/attachments', [AttachmentController::class, 'store'])->name('attachments.store');
    Route::get('/attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');
    Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/{id}/unread', [NotificationController::class, 'markAsUnread'])->name('notifications.markAsUnread');

    // Feedback
    Route::get('/feedbacks', [FeedbackController::class, 'index'])->name('feedbacks.index');
    Route::get('/tickets/{ticket}/feedback', [FeedbackController::class, 'create'])->name('feedbacks.create');
    Route::post('/tickets/{ticket}/feedback', [FeedbackController::class, 'store'])->name('feedbacks.store');
    Route::get('/feedbacks/{feedback}', [FeedbackController::class, 'show'])->name('feedbacks.show');
    Route::delete('/feedbacks/{feedback}', [FeedbackController::class, 'destroy'])->name('feedbacks.destroy');

    // Activity Logs (Admin / IT Staff only)
    Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

        // Export Activity Logs (Excel / PDF)
        Route::get('/activity-logs/export/{type}', [ActivityLogController::class, 'export'])
            ->name('activity-logs.export');
    });

});

require __DIR__ . '/auth.php';
