<?php

use App\Models\OjtAttendance;

uses(Tests\TestCase::class);

test('it calculates rendered time to the exact completed minute', function () {
    $attendance = new OjtAttendance([
        'date' => '2026-10-12',
        'am_in' => '08:17:00',
        'am_out' => '11:42:00',
        'pm_in' => '13:12:00',
        'pm_out' => '16:48:00',
    ]);

    expect($attendance->calculateRenderedMinutes())->toBe(421)
        ->and($attendance->calculateDailyHours())->toBe(7.02)
        ->and(OjtAttendance::formatDuration($attendance->calculateRenderedMinutes()))->toBe('7 hrs 1 min');
});

test('it applies OJT session windows and the daily eight hour cap', function () {
    $attendance = new OjtAttendance([
        'date' => '2026-10-12',
        'am_in' => '07:30:00',
        'am_out' => '12:30:00',
        'pm_in' => '12:45:00',
        'pm_out' => '17:30:00',
    ]);

    expect($attendance->calculateRenderedMinutes())->toBe(480);
});

test('it excludes incomplete and reversed attendance sessions', function () {
    $attendance = new OjtAttendance([
        'date' => '2026-10-12',
        'am_in' => '08:00:00',
        'pm_in' => '14:00:00',
        'pm_out' => '13:00:00',
    ]);

    expect($attendance->calculateRenderedMinutes())->toBe(0);
});

test('it carries elapsed seconds across sessions before counting whole minutes', function () {
    $attendance = new OjtAttendance([
        'date' => '2026-10-12',
        'am_in' => '08:00:30',
        'am_out' => '08:01:00',
        'pm_in' => '13:00:30',
        'pm_out' => '13:01:00',
    ]);

    expect($attendance->calculateRenderedMinutes())->toBe(1);
});

test('it formats rendered minutes without decimal hour rounding', function () {
    expect(OjtAttendance::formatDuration(65))->toBe('1 hr 5 mins')
        ->and(OjtAttendance::formatDuration(60))->toBe('1 hr 0 mins')
        ->and(OjtAttendance::formatDuration(0))->toBe('0 hrs 0 mins');
});
