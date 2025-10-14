<?php

use App\Http\Controllers\TaskScheduleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

// ✅ Welcome page
Route::get('/', function () {
    return view('welcome');
});

// ✅ Dashboard (only for logged-in users)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ✅ Authenticated routes
Route::middleware('auth')->group(function () {

    /**
     * 🔹 User Profile
     */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**
     * 🔹 Tickets
     */
    Route::get('/tickets/mine', [TicketController::class, 'mine'])->name('tickets.mine');
    Route::get('/tickets/departments', [TicketController::class, 'byDepartment'])->name('tickets.departments');
    Route::resource('tickets', TicketController::class);

    /**
     * 🔹 Categories, Departments, Task Schedules, and Meetings
     */
    Route::resource('categories', CategoryController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('tasks', TaskScheduleController::class);
    Route::resource('meetings', MeetingController::class);

    /**
     * 🔹 Comments (for tickets)
     */
    Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])
    ->name('tickets.comments.store');

    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

        // ✅ Attachments routes
Route::post('/tickets/{ticket}/attachments', [App\Http\Controllers\AttachmentController::class, 'store'])->name('attachments.store');
Route::get('/attachments/{attachment}/download', [App\Http\Controllers\AttachmentController::class, 'download'])->name('attachments.download');
Route::delete('/attachments/{attachment}', [App\Http\Controllers\AttachmentController::class, 'destroy'])->name('attachments.destroy');

    /**
     * 🔹 Notifications
     */
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/{id}/unread', [NotificationController::class, 'markAsUnread'])->name('notifications.markAsUnread');



});

require __DIR__ . '/auth.php';
