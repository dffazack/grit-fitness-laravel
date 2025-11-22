<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

beforeEach(function () {
    // Create an admin user once for all tests in this file
    $adminUser = User::factory()->create(['role' => 'admin']);
    $this->actingAs($adminUser, 'admin');
});

test('unauthenticated user is redirected from admin dashboard', function () {
    Auth::logout();
    $response = $this->get('/admin/dashboard');

    $response->assertRedirect(route('admin.login'));
});

test('authenticated admin can access dashboard', function () {
    $response = $this->get('/admin/dashboard');

    $response->assertStatus(200);
});