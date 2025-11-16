<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\ClassSchedule;
use App\Models\Trainer;
use App\Models\ClassList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminScheduleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    public function test_admin_can_view_schedules_index_page(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get(route('admin.schedules.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_store_a_new_schedule(): void
    {
        $admin = Admin::factory()->create();
        $trainer = Trainer::factory()->create();
        $classList = ClassList::factory()->create();

        $scheduleData = [
            'class_list_id' => $classList->id,
            'day' => 'Senin',
            'start_time' => '10:00',
            'end_time' => '11:00',
            'trainer_id' => $trainer->id,
            'max_quota' => 15,
            'type' => 'Yoga',
            'description' => 'A relaxing yoga session.',
        ];

        $response = $this->actingAs($admin, 'admin')->post(route('admin.schedules.store'), $scheduleData);

        $response->assertRedirect(route('admin.schedules.index'));
        $this->assertDatabaseHas('class_schedules', [
            'class_list_id' => $classList->id,
            'day' => 'Senin',
        ]);
    }

    public function test_admin_can_update_a_schedule(): void
    {
        $admin = Admin::factory()->create();
        $classList = ClassList::factory()->create();
        $schedule = ClassSchedule::factory()->create([
            'class_list_id' => $classList->id,
            'custom_class_name' => null,
        ]);
        $trainer = Trainer::factory()->create();

        $updateData = [
            'class_list_id' => $classList->id,
            'day' => 'Selasa',
            'start_time' => '12:00',
            'end_time' => '13:00',
            'trainer_id' => $trainer->id,
            'max_quota' => 20,
            'type' => 'HIIT',
            'description' => 'An intense HIIT session.',
        ];

        $response = $this->actingAs($admin, 'admin')->put(route('admin.schedules.update', $schedule), $updateData);

        $response->assertRedirect(route('admin.schedules.index'));
        $this->assertDatabaseHas('class_schedules', [
            'id' => $schedule->id,
            'day' => 'Selasa',
            'max_quota' => 20,
        ]);
    }

    public function test_admin_can_delete_a_schedule(): void
    {
        $admin = Admin::factory()->create();
        $schedule = ClassSchedule::factory()->create();

        $response = $this->actingAs($admin, 'admin')->delete(route('admin.schedules.destroy', $schedule));

        $response->assertRedirect(route('admin.schedules.index'));
        $this->assertSoftDeleted('class_schedules', ['id' => $schedule->id]);
    }
}
