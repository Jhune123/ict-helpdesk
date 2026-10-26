<?php

namespace Tests\Feature;

use App\Models\MaintenanceSchedule;
use App\Models\MaintenanceCompletion;
use App\Models\User;
use App\Notifications\MaintenanceRescheduledNotification;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MaintenanceRescheduledNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_marking_a_schedule_done_notifies_each_assignee_about_the_next_date(): void
    {
        Notification::fake();
        Carbon::setTestNow('2026-10-23 10:00:00');

        Role::firstOrCreate(['name' => 'it_staff', 'guard_name' => 'web']);
        $staff = collect([
            User::factory()->create(['role' => 'it_staff']),
            User::factory()->create(['role' => 'it_staff']),
        ]);
        $staff->each(fn (User $user) => $user->assignRole('it_staff'));

        $schedule = MaintenanceSchedule::create([
            'office_college' => 'ICT Office',
            'frequency' => 'weekly',
            'title' => 'Office Laptop',
            'last_run_date' => '2026-10-16',
            'next_run_date' => '2026-10-23',
            'description' => json_encode(['tasks' => ['Clean the keyboard'], 'remarks' => 'Completed successfully']),
        ]);
        $schedule->assignees()->sync($staff->pluck('id')->all());

        $this->actingAs($staff->first())
            ->post(route('maintenance.complete', $schedule->id))
            ->assertRedirect();

        $schedule->refresh();
        $this->assertSame('2026-10-23', $schedule->last_run_date->format('Y-m-d'));
        $this->assertSame('2026-10-30', $schedule->next_run_date->format('Y-m-d'));

        $completion = MaintenanceCompletion::firstOrFail();
        $this->assertSame('2026-10-23', $completion->snapshot['last_run_date']);
        $this->assertSame('2026-10-30', $completion->snapshot['next_run_date']);
        $this->assertSame('Clean the keyboard', json_decode($completion->snapshot['description'], true)['tasks'][0]);
        $this->assertSame($staff->pluck('name')->all(), collect($completion->snapshot['assignees'])->pluck('name')->all());

        $this->get(route('maintenance.completions.show', $completion))
            ->assertOk()
            ->assertSee('Completed Maintenance Record')
            ->assertSee('Clean the keyboard');

        foreach ($staff as $user) {
            Notification::assertSentTo(
                $user,
                MaintenanceRescheduledNotification::class,
                function (MaintenanceRescheduledNotification $notification, array $channels) use ($user, $schedule) {
                    $mail = $notification->toMail($user);

                    return $channels === ['database', 'mail']
                        && $notification->toArray($user)['maintenance_id'] === $schedule->id
                        && str_contains($notification->toArray($user)['message'], 'October 30, 2026')
                        && $mail->subject === 'Next maintenance schedule: '.$schedule->title;
                }
            );
        }

        Carbon::setTestNow();
    }
}
