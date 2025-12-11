<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Mockery;

class GoogleLoginTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function google_callback_creates_or_logs_in_user()
    {
        // Fake user dari Google (tanpa benar2 akses Google)
        $googleUser = new \Laravel\Socialite\Two\User;
        $googleUser->id = '1234567890';
        $googleUser->name = 'Testing User';
        $googleUser->email = 'testing@example.com';
        $googleUser->avatar = 'https://example.com/avatar.png';

        // Mock Socialite supaya return data palsu
        Socialite::shouldReceive('driver->user')->andReturn($googleUser);

        // Panggil route callback
        $response = $this->get('/auth/google/callback');

        // User harus otomatis masuk database
        $this->assertDatabaseHas('users', [
            'email' => 'testing@example.com'
        ]);

        // User harus diarahkan ke dashboard (atau halamanmu)
        $response->assertRedirect('/membership');
    }
}
