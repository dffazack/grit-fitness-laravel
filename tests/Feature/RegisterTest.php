<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('user can register successfully', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'testuser@example.com',
        'phone' => '081234567890',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    // Redirect setelah register
    $response->assertRedirect('/email/verify');

    // Pastikan user muncul di database
    $this->assertDatabaseHas('users', [
        'email' => 'testuser@example.com',
    ]);
});