<?php

namespace Tests\Feature;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MeetingScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_meeting_list_wraps_long_text_fields()
    {
        $user = User::factory()->create();
        $longTitle = str_repeat('MeetingTitleWithoutSpaces', 16);
        $longRemarks = str_repeat('MeetingRemarksWithoutSpaces', 16);
        $meeting = Meeting::create([
            'title' => $longTitle,
            'date' => '2026-10-25',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'location' => 'Main Hall',
            'participants' => 'Staff',
            'remarks' => $longRemarks,
        ]);

        $this->actingAs($user)
            ->get(route('meetings.index'))
            ->assertOk()
            ->assertSee($longTitle)
            ->assertSee($longRemarks)
            ->assertSee('overflow-wrap: anywhere', false)
            ->assertDontSee('display nowrap', false);
    }

    public function test_meeting_calendar_highlights_scheduled_dates_and_displays_remarks()
    {
        $user = User::factory()->create();
        Meeting::create([
            'title' => 'Planning Meeting',
            'date' => '2026-10-25',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'location' => 'Main Hall',
            'participants' => 'Staff',
            'remarks' => 'Bring the updated project plan.',
        ]);

        $this->actingAs($user)
            ->get(route('meetings.calendar'))
            ->assertOk()
            ->assertSee('meeting-scheduled', false)
            ->assertSee('2026-10-25', false)
            ->assertSee('Bring the updated project plan.', false)
            ->assertSee('meeting-event-remarks', false);
    }
}
