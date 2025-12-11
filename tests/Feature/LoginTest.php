<?php

use App\Models\User;

test('user can login successfully', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
        'membership_status' => 'non-member',
    ]);

    $response = $this->postJson('/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    // Karena user non-member → redirect ke /membership
    $response->assertRedirect('/membership');
});
