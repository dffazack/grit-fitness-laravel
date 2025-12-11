<?php

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

test('user must verify email', function () {

    // Fake email notifications
    Notification::fake();

    // Buat user
    $user = User::factory()->unverified()->create();

    // Kirim email verifikasi
    Notification::send($user, new VerifyEmail);

    // Ambil link verifikasi yang dikirim
    Notification::assertSentTo($user, VerifyEmail::class);

    $verifyUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    // Hit endpoint verifikasi
    $this->actingAs($user)
        ->get($verifyUrl)
        ->assertRedirect(route('membership'));

    // Pastikan sudah verified
    $this->assertNotNull($user->fresh()->email_verified_at);
});
