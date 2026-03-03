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

/* 📺 PUBLIC LIVE TV MONITOR */
Route::get('/mis-queue/live-tv', [QueueController::class, 'liveTV'])->name('queues.live-tv');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (Dashboard & Analytics)
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
| MAIN APPLICATION ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

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

    /* TICKETS & EXPORTS */
    Route::get('/tickets/mine', [TicketController::class, 'mine'])->name('tickets.mine');
    Route::get('/tickets/departments', [TicketController::class, 'byDepartment'])->name('tickets.departments');
    
    Route::prefix('tickets/export')->group(function () {
        Route::get('/pdf', [TicketController::class, 'exportPdf'])->name('tickets.export.pdf');
        Route::get('/excel', [TicketController::class, 'exportExcel'])->name('tickets.export.excel');
        Route::get('/csv', [TicketController::class, 'exportCsv'])->name('tickets.export.csv');
    });

    Route::get('/tickets/{ticket}/job-order', [TicketController::class, 'jobOrderPdf'])->name('tickets.jobOrderPdf');
    Route::resource('tickets', TicketController::class);

    /* CATEGORIES & DEPARTMENTS */
    Route::resource('categories', CategoryController::class);
    Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
        Route::resource('departments', DepartmentController::class)->except(['create', 'show', 'edit']);
    });

    /* PREVENTIVE MAINTENANCE (PMS) */
    Route::get('maintenance', [MaintenanceScheduleController::class, 'index'])->name('maintenance.index');
    Route::get('maintenance/export/pdf', [MaintenanceScheduleController::class, 'exportPdf'])->name('maintenance.pdf');
    Route::get('maintenance/{id}/job-order', [MaintenanceScheduleController::class, 'downloadJobOrder'])->name('maintenance.job_order');
    Route::get('maintenance/{maintenance}', [MaintenanceScheduleController::class, 'show'])->name('maintenance.show');

    Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
        Route::get('maintenance/create', [MaintenanceScheduleController::class, 'create'])->name('maintenance.create');
        Route::post('maintenance', [MaintenanceScheduleController::class, 'store'])->name('maintenance.store');
        Route::post('maintenance/{id}/complete', [MaintenanceScheduleController::class, 'completeTask'])->name('maintenance.complete');
        Route::get('maintenance/{maintenance}/edit', [MaintenanceScheduleController::class, 'edit'])->name('maintenance.edit');
        Route::put('maintenance/{maintenance}', [MaintenanceScheduleController::class, 'update'])->name('maintenance.update');
        Route::delete('maintenance/{maintenance}', [MaintenanceScheduleController::class, 'destroy'])->name('maintenance.destroy');
    });

    /* ASSETS */
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

    /* OTHER TOOLS */
    Route::resource('tasks', TaskScheduleController::class);
    Route::resource('meetings', MeetingController::class);
    Route::get('/meetings/calendar', [MeetingController::class, 'calendar'])->name('meetings.calendar');

    /* 🗑️ CONDEMNED EQUIPMENT (Fixed Exports & Order) */
    Route::prefix('condemned-equipment')->group(function () {
        // Exports (placed above wildcards)
        Route::get('/export/pdf', [CondemnedEquipmentController::class, 'exportPdf'])->name('condemned-equipment.export.pdf');
        Route::get('/export/excel', [CondemnedEquipmentController::class, 'exportExcel'])->name('condemned-equipment.export.excel');
        Route::get('/export/csv', [CondemnedEquipmentController::class, 'exportCsv'])->name('condemned-equipment.export.csv');

        // Main Views
        Route::get('/', [CondemnedEquipmentController::class, 'index'])->name('condemned-equipment.index');

        Route::middleware(RoleMiddleware::class . ':admin|it_staff')->group(function () {
            Route::get('/create', [CondemnedEquipmentController::class, 'create'])->name('condemned-equipment.create');
            Route::post('/', [CondemnedEquipmentController::class, 'store'])->name('condemned-equipment.store');
            Route::get('/{condemnedEquipment}/edit', [CondemnedEquipmentController::class, 'edit'])->name('condemned-equipment.edit');
            Route::put('/{condemnedEquipment}', [CondemnedEquipmentController::class, 'update'])->name('condemned-equipment.update');
            Route::delete('/{condemnedEquipment}', [CondemnedEquipmentController::class, 'destroy'])->name('condemned-equipment.destroy');
        });

        // Show (Wildcard at the end)
        Route::get('/{condemnedEquipment}', [CondemnedEquipmentController::class, 'show'])->name('condemned-equipment.show');
    });

    /* SYSTEM FEEDBACK, LOGS & NOTIFICATIONS */
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->middleware(RoleMiddleware::class . ':admin|it_staff')->name('activity-logs.index');
    Route::resource('feedbacks', FeedbackController::class);
});

/* AUTH */
require __DIR__ . '/auth.php';