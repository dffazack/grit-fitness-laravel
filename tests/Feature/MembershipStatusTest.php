<?php

use App\Models\User;

test('active members can access dashboard', function () {
    $user = User::factory()->create([
        'membership_status' => 'active',
        'email_verified_at' => now(),
        'role' => 'member',
    ]);

    $response = $this->actingAs($user)->get('/member/dashboard');

    $response->assertStatus(200);
});

test('non members are redirected to membership page', function () {
    $user = User::factory()->create([
        'membership_status' => 'non-member',
        'email_verified_at' => now(),
        'role' => 'member',
    ]);

    $response = $this->actingAs($user)->get('/member/dashboard');

    $response->assertRedirect('/membership');
    $response->assertSessionHas('info', 'Silakan pilih paket membership terlebih dahulu.');
});

test('pending members see info message on dashboard', function () {
    $user = User::factory()->create([
        'membership_status' => 'pending',
        'email_verified_at' => now(),
        'role' => 'member',
    ]);

    $response = $this->actingAs($user)->get('/member/dashboard');

    $response->assertStatus(200);
    $response->assertSee('Pembayaran Anda sedang diproses oleh admin.');
});

test('expired members are redirected to payment page', function () {
    $user = User::factory()->create([
        'membership_status' => 'expired',
        'email_verified_at' => now(),
        'role' => 'member',
    ]);

    $response = $this->actingAs($user)->get('/member/dashboard');

    $response->assertRedirect('/member/payment');
    $response->assertSessionHas('error', 'Membership Anda telah berakhir. Silakan perpanjang membership.');
});
