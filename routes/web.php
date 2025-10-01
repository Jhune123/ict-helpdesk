<?php

use App\Http\Controllers\TaskScheduleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MeetingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

// Notifications routes
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.markAsRead');

    Route::post('/notifications/{id}/unread', [NotificationController::class, 'markAsUnread'])
        ->name('notifications.markAsUnread');
});

// Tickets by department
Route::get('/tickets/departments', [TicketController::class, 'byDepartment'])
    ->name('tickets.departments');

// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (default Breeze dashboard)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Protected routes (only logged-in users)
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Custom "My Tickets" page
    Route::get('/tickets/mine', [TicketController::class, 'mine'])->name('tickets.mine');

    // Tickets CRUD
    Route::resource('tickets', TicketController::class);

    // Categories CRUD
    Route::resource('categories', CategoryController::class);

    // Departments CRUD
    Route::resource('departments', DepartmentController::class);

    // Task Schedule CRUD
    Route::resource('tasks', TaskScheduleController::class);

    // ✅ Meeting Schedule CRUD (protected)
    Route::resource('meetings', MeetingController::class);
});

require __DIR__.'/auth.php';
