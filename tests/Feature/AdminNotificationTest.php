<?php

use App\Models\User;
use App\Models\Notification;

beforeEach(function () {
    // Create an admin user once for all tests in this file
    $this->adminUser = User::factory()->create(['role' => 'admin']);
    $this->actingAs($this->adminUser, 'admin');
});

test('admin can view notifications page', function () {
    $response = $this->get(route('admin.notifications.index'));

    $response->assertStatus(200);
    $response->assertSee('Banner Notifikasi Homepage');
});

test('admin can create a new notification', function () {
    $notificationData = [
        'title' => 'Test Notification',
        'message' => 'This is a test notification.',
        'type' => 'promo',
        'start_date' => now()->toDateString(),
        'end_date' => now()->addWeek()->toDateString(),
        'is_active' => '1',
    ];

    $response = $this->postJson(route('admin.notifications.store'), $notificationData);

    $response->assertRedirect(route('admin.notifications.index'));
    $this->assertDatabaseHas('notifications', [
        'title' => 'Test Notification',
        'message' => 'This is a test notification.',
    ]);
});

test('admin can update a notification', function () {
    $notification = Notification::factory()->create();

    $updateData = [
        'title' => 'Updated Title',
        'message' => 'Updated message content.',
        'type' => $notification->type,
        'start_date' => $notification->start_date->toDateString(),
        'end_date' => $notification->end_date->toDateString(),
    ];

    $response = $this->putJson(route('admin.notifications.update', $notification), $updateData);

    $response->assertRedirect(route('admin.notifications.index'));
    $this->assertDatabaseHas('notifications', [
        'id' => $notification->id,
        'title' => 'Updated Title',
    ]);
});

test('admin can delete a notification', function () {
    $notification = Notification::factory()->create();

    $response = $this->deleteJson(route('admin.notifications.destroy', $notification));

    $response->assertRedirect(route('admin.notifications.index'));
    $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
});