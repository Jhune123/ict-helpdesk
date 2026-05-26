<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
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
use App\Http\Controllers\QueueController;
use App\Http\Controllers\MaintenanceScheduleController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/mis-queue/live-tv', [QueueController::class, 'liveTV'])->name('queues.live-tv');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (Dashboard & Analytics)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::get('/dashboard/analytics', [DashboardController::class, 'analytics'])->name('dashboard.analytics');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/dashboard/assets-analytics', [AssetController::class, 'analytics'])->name('assets.analytics');
});

/*
|--------------------------------------------------------------------------
| MAIN APPLICATION ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /* 👥 USER MANAGEMENT */
    Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
        Route::resource('users', UserController::class);
    });

    /* PROFILE */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* 🎫 MIS QUEUING SYSTEM */
    Route::prefix('mis-queue')->group(function () {
        Route::get('/', [QueueController::class, 'operator'])->name('queues.index');
        Route::get('/operator', [QueueController::class, 'operator'])->name('queues.operator');
        Route::get('/pdf/report', [QueueController::class, 'pdfReport'])->name('queues.pdf.report');

        Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
            Route::post('/add', [QueueController::class, 'add'])->name('queues.add');
            Route::patch('/serve/{queue}', [QueueController::class, 'serve'])->name('queues.serve');
            Route::patch('/complete/{queue}', [QueueController::class, 'complete'])->name('queues.complete');
            Route::post('/clear', [QueueController::class, 'clear'])->name('queues.clear');
        });
    });

    /* 🎫 TICKETS, COMMENTS & EXPORTS */
    Route::get('/tickets/mine', [TicketController::class, 'mine'])->name('tickets.mine');
    
    Route::prefix('tickets/export')->group(function () {
        Route::get('/pdf', [TicketController::class, 'exportPdf'])->name('tickets.export.pdf');
        Route::get('/excel', [TicketController::class, 'exportExcel'])->name('tickets.export.excel');
        Route::get('/csv', [TicketController::class, 'exportCsv'])->name('tickets.export.csv');
    });

    Route::get('/tickets/{ticket}/job-order', [TicketController::class, 'jobOrderPdf'])->name('tickets.jobOrder');
    Route::get('/tickets/{ticket}/job-order-pdf', [TicketController::class, 'jobOrderPdf'])->name('tickets.jobOrderPdf');
    
    // Explicit production-safe ticket routes
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    
    Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
        Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
        Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
        Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');
    });
    
    // Comment Routes
    Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])->name('tickets.comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    /* 📝 FEEDBACK SYSTEM */
    Route::get('/feedbacks/create/{ticket}', [FeedbackController::class, 'create'])->name('feedbacks.create');
    Route::post('/feedbacks/store/{ticket}', [FeedbackController::class, 'store'])->name('feedbacks.store');
    Route::get('/feedbacks/{feedback}/download-pdf', [FeedbackController::class, 'downloadPdf'])->name('feedbacks.download-pdf');
    
    // Explicit production-safe feedback routes
    Route::get('/feedbacks', [FeedbackController::class, 'index'])->name('feedbacks.index');
    Route::get('/feedbacks/{feedback}', [FeedbackController::class, 'show'])->name('feedbacks.show');

    Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
        Route::get('/feedbacks/{feedback}/edit', [FeedbackController::class, 'edit'])->name('feedbacks.edit');
        Route::put('/feedbacks/{feedback}', [FeedbackController::class, 'update'])->name('feedbacks.update');
        Route::delete('/feedbacks/{feedback}', [FeedbackController::class, 'destroy'])->name('feedbacks.destroy');
    });

    /* 🗂️ CATEGORIES & DEPARTMENTS */
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

    Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        
        Route::resource('departments', DepartmentController::class)->except(['create', 'show', 'edit']);
    });

    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

    /* 🛠️ PREVENTIVE MAINTENANCE (PMS) */
    Route::prefix('maintenance')->group(function () {
        Route::get('/', [MaintenanceScheduleController::class, 'index'])->name('maintenance.index');
        Route::get('/export/pdf', [MaintenanceScheduleController::class, 'exportPdf'])->name('maintenance.pdf');
        
        Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
            Route::get('/create', [MaintenanceScheduleController::class, 'create'])->name('maintenance.create');
            Route::post('/', [MaintenanceScheduleController::class, 'store'])->name('maintenance.store');
            Route::post('/{id}/complete', [MaintenanceScheduleController::class, 'completeTask'])->name('maintenance.complete');
            Route::get('/{maintenance}/edit', [MaintenanceScheduleController::class, 'edit'])->name('maintenance.edit');
            Route::put('/{maintenance}', [MaintenanceScheduleController::class, 'update'])->name('maintenance.update');
            Route::delete('/{maintenance}', [MaintenanceScheduleController::class, 'destroy'])->name('maintenance.destroy');
        });

        Route::get('/{id}/job-order', [MaintenanceScheduleController::class, 'downloadJobOrder'])->name('maintenance.job_order');
        Route::get('/{maintenance}', [MaintenanceScheduleController::class, 'show'])->name('maintenance.show');
    });

    /* 🖥️ ASSETS */
    Route::get('/assets/export/pdf', [AssetController::class, 'exportPdf'])->name('assets.export.pdf');
    Route::get('/assets/print', [AssetController::class, 'print'])->name('assets.print');
    
    Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
        Route::get('/assets/create', [AssetController::class, 'create'])->name('assets.create');
        Route::post('/assets', [AssetController::class, 'store'])->name('assets.store');
        Route::get('/assets/{asset}/edit', [AssetController::class, 'edit'])->name('assets.edit');
        Route::put('/assets/{asset}', [AssetController::class, 'update'])->name('assets.update');
        Route::delete('/assets/{asset}', [AssetController::class, 'destroy'])->name('assets.destroy');
    });
    
    Route::get('/assets', [AssetController::class, 'index'])->name('assets.index');
    Route::get('/assets/{asset}', [AssetController::class, 'show'])->name('assets.show');

    /* 📅 OTHER TOOLS (TASKS & MEETINGS) */
    Route::get('/tasks/export/pdf', [TaskScheduleController::class, 'exportPdf'])->name('tasks.export.pdf');
    Route::get('/meetings/calendar', [MeetingController::class, 'calendar'])->name('meetings.calendar');
    
    // Explicit Admin/IT-Staff routes placed BEFORE wildcard handlers
    Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
        Route::get('/tasks/create', [TaskScheduleController::class, 'create'])->name('tasks.create');
        Route::post('/tasks', [TaskScheduleController::class, 'store'])->name('tasks.store');
        Route::get('/tasks/{task}/edit', [TaskScheduleController::class, 'edit'])->name('tasks.edit');
        Route::put('/tasks/{task}', [TaskScheduleController::class, 'update'])->name('tasks.update');
        Route::delete('/tasks/{task}', [TaskScheduleController::class, 'destroy'])->name('tasks.destroy');

        Route::get('/meetings/create', [MeetingController::class, 'create'])->name('meetings.create');
        Route::post('/meetings', [MeetingController::class, 'store'])->name('meetings.store');
        Route::get('/meetings/{meeting}/edit', [MeetingController::class, 'edit'])->name('meetings.edit');
        Route::put('/meetings/{meeting}', [MeetingController::class, 'update'])->name('meetings.update');
        Route::delete('/meetings/{meeting}', [MeetingController::class, 'destroy'])->name('meetings.destroy');
    });

    // General Authenticated viewing routes containing baseline wildcards
    Route::get('/tasks', [TaskScheduleController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{task}', [TaskScheduleController::class, 'show'])->name('tasks.show');
    
    Route::get('/meetings', [MeetingController::class, 'index'])->name('meetings.index');
    Route::get('/meetings/{meeting}', [MeetingController::class, 'show'])->name('meetings.show');

    /* 🗑️ CONDEMNED EQUIPMENT */
    Route::prefix('condemned-equipment')->group(function () {
        Route::get('/export/pdf', [CondemnedEquipmentController::class, 'exportPdf'])->name('condemned-equipment.export.pdf');
        Route::get('/export/excel', [CondemnedEquipmentController::class, 'exportExcel'])->name('condemned-equipment.export.excel');
        Route::get('/export/csv', [CondemnedEquipmentController::class, 'exportCsv'])->name('condemned-equipment.export.csv');
        Route::get('/', [CondemnedEquipmentController::class, 'index'])->name('condemned-equipment.index');

        Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
            Route::get('/create', [CondemnedEquipmentController::class, 'create'])->name('condemned-equipment.create');
            Route::post('/', [CondemnedEquipmentController::class, 'store'])->name('condemned-equipment.store');
            Route::get('/{condemnedEquipment}/edit', [CondemnedEquipmentController::class, 'edit'])->name('condemned-equipment.edit');
            Route::put('/{condemnedEquipment}', [CondemnedEquipmentController::class, 'update'])->name('condemned-equipment.update');
            Route::delete('/{condemnedEquipment}', [CondemnedEquipmentController::class, 'destroy'])->name('condemned-equipment.destroy');
        });
        
        Route::get('/{ticket}/pdf', [CondemnedEquipmentController::class, 'downloadPdf'])->name('condemned-equipment.pdf');
        Route::get('/{id}/print', [CondemnedEquipmentController::class, 'downloadCertificate'])->name('condemned-equipment.print');
        Route::get('/{condemnedEquipment}', [CondemnedEquipmentController::class, 'show'])->name('condemned-equipment.show');
    });

    /* 🔔 SYSTEM LOGS & NOTIFICATIONS */
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/{id}/unread', [NotificationController::class, 'markAsUnread'])->name('notifications.markAsUnread');
    
    Route::middleware(RoleMiddleware::class . ':admin|it_staff')->prefix('activity-logs')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('/export', [ActivityLogController::class, 'export'])->name('activity-logs.export');
    });
});

/* AUTH */
require __DIR__ . '/auth.php';