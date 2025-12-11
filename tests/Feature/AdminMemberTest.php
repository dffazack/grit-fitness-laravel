<?php

use App\Models\User;

beforeEach(function () {
    $adminUser = User::factory()->create(['role' => 'admin']);
    $this->actingAs($adminUser, 'admin');
});

test('admin can view members page', function () {
    $response = $this->get('/admin/members');

    $response->assertStatus(200);
});

test('admin can create a new member', function () {
    $response = $this->post('/admin/members', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('admin.members.index'));
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
    ]);
});

test('admin can edit a member', function () {
    $user = User::factory()->create();

    $response = $this->put('/admin/members/'.$user->id, [
        'name' => 'Updated Name',
        'email' => $user->email,
    ]);

    $response->assertRedirect(route('admin.members.index'));
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Updated Name',
    ]);
});

test('admin can delete a member', function () {
    $user = User::factory()->create();

    $response = $this->delete('/admin/members/'.$user->id);

    $response->assertRedirect(route('admin.members.index'));
    $this->assertSoftDeleted('users', [
        'id' => $user->id,
    ]);
});
