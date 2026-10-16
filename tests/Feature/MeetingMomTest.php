<?php

namespace Tests\Feature;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MeetingMomTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_and_it_staff_can_upload_view_download_and_replace_meeting_mom()
    {
        Storage::fake('local');
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'it_staff', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $itStaff = User::factory()->create();
        $itStaff->assignRole('it_staff');
        $meeting = $this->createMeeting();

        $this->actingAs($admin)
            ->get(route('meetings.index'))
            ->assertOk()
            ->assertSee('MOM');

        $this->post(route('meetings.mom.upload', $meeting), [
            'mom' => UploadedFile::fake()->create('meeting-minutes.pdf', 20, 'application/pdf'),
        ])->assertRedirect(route('meetings.index'));

        $meeting->refresh();
        $oldPath = $meeting->mom_path;
        $this->assertSame('meeting-minutes.pdf', $meeting->mom_original_name);
        Storage::disk('local')->assertExists($oldPath);

        $this->actingAs($itStaff)
            ->get(route('meetings.mom.view', $meeting))
            ->assertOk()
            ->assertHeader('content-disposition', 'inline; filename=meeting-minutes.pdf');

        $this->get(route('meetings.mom.download', $meeting))
            ->assertOk()
            ->assertHeader('content-disposition', 'attachment; filename=meeting-minutes.pdf');

        $this->post(route('meetings.mom.upload', $meeting), [
            'mom' => UploadedFile::fake()->create('updated-minutes.docx', 30, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
        ])->assertRedirect(route('meetings.index'));

        $meeting->refresh();
        $this->assertSame('updated-minutes.docx', $meeting->mom_original_name);
        $this->assertNotSame($oldPath, $meeting->mom_path);
        Storage::disk('local')->assertMissing($oldPath);
        Storage::disk('local')->assertExists($meeting->mom_path);
    }

    public function test_clients_cannot_upload_view_or_download_meeting_mom()
    {
        Storage::fake('local');
        Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);
        $client = User::factory()->create();
        $client->assignRole('client');
        $meeting = $this->createMeeting();

        $this->actingAs($client)
            ->post(route('meetings.mom.upload', $meeting), [
                'mom' => UploadedFile::fake()->create('minutes.pdf', 10, 'application/pdf'),
            ])
            ->assertForbidden();

        $this->get(route('meetings.mom.view', $meeting))->assertForbidden();
        $this->get(route('meetings.mom.download', $meeting))->assertForbidden();
    }

    private function createMeeting(): Meeting
    {
        return Meeting::create([
            'title' => 'Meeting with MOM',
            'date' => '2026-10-25',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'location' => 'Main Hall',
        ]);
    }
}
