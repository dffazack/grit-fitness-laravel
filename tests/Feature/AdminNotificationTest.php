<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        // Create an admin user once for all tests in this class
        $this->adminUser = User::factory()->create(['role' => 'admin']);
    }

    public function test_admin_can_view_notifications_page(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.notifications.index'));

        $response->assertStatus(200);
        $response->assertSee('Banner Notifikasi Homepage');
    }

    public function test_admin_can_create_a_new_notification(): void
    {
        $notificationData = [
            'title' => 'Test Notification',
            'message' => 'This is a test notification.',
            'type' => 'promo',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addWeek()->toDateString(),
            'is_active' => '1',
        ];

        $response = $this->actingAs($this->adminUser)->post(route('admin.notifications.store'), $notificationData);

        $response->assertRedirect(route('admin.notifications.index'));
        $this->assertDatabaseHas('notifications', [
            'title' => 'Test Notification',
            'message' => 'This is a test notification.',
        ]);
    }

    public function test_admin_can_update_a_notification(): void
    {
        $notification = Notification::factory()->create();

        $updateData = [
            'title' => 'Updated Title',
            'message' => 'Updated message content.',
            'type' => $notification->type,
            'start_date' => $notification->start_date->toDateString(),
            'end_date' => $notification->end_date->toDateString(),
        ];

        $response = $this->actingAs($this->adminUser)->put(route('admin.notifications.update', $notification), $updateData);

        $response->assertRedirect(route('admin.notifications.index'));
        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_admin_can_delete_a_notification(): void
    {
        $notification = Notification::factory()->create();

        $response = $this->actingAs($this->adminUser)->delete(route('admin.notifications.destroy', $notification));

        $response->assertRedirect(route('admin.notifications.index'));
        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }
}
