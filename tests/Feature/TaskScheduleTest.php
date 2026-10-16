<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TaskScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_creation_saves_title_to_task_and_linked_ticket(): void
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'it_staff', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('admin');
        $bryan = User::factory()->create(['name' => 'Bryan']);
        $bryan->assignRole('it_staff');
        $department = Department::create(['name' => 'ICTO']);
        $category = Category::create(['name' => 'Event Support']);

        $this->actingAs($user)
            ->get(route('tasks.create'))
            ->assertOk()
            ->assertSee('name="title"', false)
            ->assertSee('name="category_id"', false)
            ->assertSeeText('Event Support');

        $this->actingAs($user)->post(route('tasks.store'), [
            'title' => 'Business Orientation Support',
            'date' => '2026-10-16',
            'description' => 'Set Up Led Wall',
            'requested_by' => 'UIO',
            'department_id' => $department->id,
            'category_id' => $category->id,
            'location' => 'Gymnasium',
            'start_time' => '08:04',
            'end_time' => '17:04',
            'assigned_to' => 'Bryan',
            'remarks' => 'Business Orientation',
        ])->assertRedirect(route('tasks.index'));

        $task = Task::query()->firstOrFail();

        $this->assertSame('Business Orientation Support', $task->title);
        $this->assertSame('UIO', $task->requested_by);
        $this->assertNotNull($task->ticket_id);
        $this->assertSame('Business Orientation Support', $task->ticket->title);
        $this->assertSame(
            "Set Up Led Wall\nLocation: Gymnasium\nSchedule Date: October 16, 2026\nTime: 08:04:00 to 17:04:00",
            $task->ticket->description
        );
        $this->assertSame($category->id, $task->ticket->category_id);
        $this->assertSame($bryan->id, $task->ticket->assigned_to);
        $this->assertSame('Business Orientation', $task->ticket->remarks);
        $this->assertSame('Gymnasium', $task->ticket->form_data['location']);
        $this->assertArrayNotHasKey('requested_by', $task->ticket->form_data);
        $this->assertArrayNotHasKey('assigned_to', $task->ticket->form_data);

        $this->get(route('tickets.show', $task->ticket_id))
            ->assertOk()
            ->assertSeeText('Business Orientation Support')
            ->assertSeeText('Set Up Led Wall')
            ->assertSeeText('Location: Gymnasium')
            ->assertSeeText('Schedule Date: October 16, 2026')
            ->assertSeeText('Time: 08:04:00 to 17:04:00')
            ->assertSeeText('Bryan')
            ->assertSeeText('Business Orientation')
            ->assertDontSeeText('Scheduled Task:')
            ->assertDontSeeText('Requested By:');

        $this->get(route('tasks.show', $task))
            ->assertOk()
            ->assertSeeText('Business Orientation Support')
            ->assertSeeText('October 16, 2026')
            ->assertSeeText('Set Up Led Wall')
            ->assertSeeText('UIO')
            ->assertSeeText('ICTO')
            ->assertSeeText('Event Support')
            ->assertSeeText('Gymnasium')
            ->assertSeeText('Bryan')
            ->assertSeeText('Business Orientation');

        $this->get(route('tasks.edit', $task))
            ->assertOk()
            ->assertSee('name="title" value="Business Orientation Support"', false)
            ->assertSee('name="requested_by" value="UIO"', false)
            ->assertSee('name="category_id"', false)
            ->assertSee('value="' . $category->id . '" selected', false);
    }

    public function test_editing_task_updates_all_linked_ticket_schedule_details(): void
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'it_staff', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('admin');
        $bryan = User::factory()->create(['name' => 'Bryan']);
        $bryan->assignRole('it_staff');
        $jhune = User::factory()->create(['name' => 'Jhune']);
        $jhune->assignRole('it_staff');
        $department = Department::create(['name' => 'ICTO']);
        $updatedDepartment = Department::create(['name' => 'Engineering']);
        $category = Category::create(['name' => 'Event Support']);
        $updatedCategory = Category::create(['name' => 'Network Support']);

        $this->actingAs($user)->post(route('tasks.store'), [
            'title' => 'Original Task',
            'date' => '2026-10-16',
            'description' => 'Original description',
            'requested_by' => 'Original Requestor',
            'department_id' => $department->id,
            'category_id' => $category->id,
            'location' => 'Old Location',
            'start_time' => '08:00',
            'end_time' => '09:00',
            'assigned_to' => 'Bryan',
            'remarks' => 'Old remarks',
        ])->assertRedirect(route('tasks.index'));

        $task = Task::query()->firstOrFail();

        $this->put(route('tasks.update', $task), [
            'title' => 'Updated Task Title',
            'date' => '2026-10-20',
            'description' => 'Updated task description',
            'requested_by' => 'Updated Requestor',
            'department_id' => $updatedDepartment->id,
            'category_id' => $updatedCategory->id,
            'location' => 'Updated Location',
            'start_time' => '10:30',
            'end_time' => '11:45',
            'assigned_to' => 'Jhune',
            'remarks' => 'Updated remarks',
        ])->assertRedirect(route('tasks.index'));

        $task->refresh();
        $ticket = $task->ticket()->firstOrFail();

        $this->assertSame('Updated Task Title', $ticket->title);
        $this->assertSame(
            "Updated task description\nLocation: Updated Location\nSchedule Date: October 20, 2026\nTime: 10:30:00 to 11:45:00",
            $ticket->description
        );
        $this->assertSame('Updated Requestor', $ticket->client_name);
        $this->assertSame('Engineering', $ticket->department);
        $this->assertSame($updatedCategory->id, $ticket->category_id);
        $this->assertSame($jhune->id, $ticket->assigned_to);
        $this->assertSame('Updated remarks', $ticket->remarks);
        $this->assertSame('2026-10-20', $ticket->form_data['task_date']);
        $this->assertSame('Network Support', $ticket->form_data['category']);
        $this->assertSame('Updated Location', $ticket->form_data['location']);
        $this->assertSame('10:30:00', $ticket->form_data['start_time']);
        $this->assertSame('11:45:00', $ticket->form_data['end_time']);
        $this->assertArrayNotHasKey('requested_by', $ticket->form_data);
        $this->assertArrayNotHasKey('assigned_to', $ticket->form_data);

        $this->get(route('tickets.show', $ticket))
            ->assertOk()
            ->assertSeeText('Updated Task Title')
            ->assertSeeText('Updated Requestor')
            ->assertSeeText('Engineering')
            ->assertSeeText('Network Support')
            ->assertSeeText('Updated Location')
            ->assertSeeText('Jhune')
            ->assertSeeText('Updated remarks')
            ->assertSeeText('Updated task description')
            ->assertSeeText('Location: Updated Location')
            ->assertSeeText('Schedule Date: October 20, 2026')
            ->assertSeeText('Time: 10:30:00 to 11:45:00')
            ->assertDontSeeText('Requested By:');
    }
}
