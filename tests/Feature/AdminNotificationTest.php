<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(Admin::factory()->create(), 'admin');
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    public function test_admin_can_view_notifications_index_page(): void
    {
        $response = $this->get(route('admin.notifications.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_store_a_new_notification(): void
    {
        $notificationData = [
            'title' => 'New Maintenance',
            'message' => 'We will be down for maintenance.',
            'type' => 'announcement',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
            'is_active' => true,
        ];

        $response = $this->post(route('admin.notifications.store'), $notificationData);

        $response->assertRedirect(route('admin.notifications.index'));
        $this->assertDatabaseHas('notifications', ['title' => 'New Maintenance']);
    }

    public function test_admin_can_update_a_notification(): void
    {
        $notification = Notification::factory()->create();

        $updateData = [
            'title' => 'Updated Maintenance',
            'message' => 'Maintenance is extended.',
            'type' => 'event',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
            'is_active' => true,
        ];

        $response = $this->put(route('admin.notifications.update', $notification), $updateData);

        $response->assertRedirect(route('admin.notifications.index'));
        $this->assertDatabaseHas('notifications', ['title' => 'Updated Maintenance']);
    }

    public function test_admin_can_delete_a_notification(): void
    {
        $notification = Notification::factory()->create();

        $response = $this->delete(route('admin.notifications.destroy', $notification));

        $response->assertRedirect(route('admin.notifications.index'));
        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }

    public function test_admin_can_toggle_notification_status(): void
    {
        $notification = Notification::factory()->create(['is_active' => true]);

        $response = $this->post(route('admin.notifications.toggle', $notification));

        $response->assertRedirect();
        $this->assertFalse($notification->fresh()->is_active);
    }
}