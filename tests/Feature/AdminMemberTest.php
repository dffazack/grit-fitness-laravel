<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMemberTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    public function test_admin_can_view_members_page(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin/members');

        $response->assertStatus(200);
    }

    public function test_admin_can_create_a_new_member(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->post('/admin/members', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect('/admin/members');
    }

    public function test_admin_can_edit_a_member(): void
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin, 'admin')->put("/admin/members/{$user->id}", [
            'name' => 'Updated Name',
            'email' => $user->email,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);

        $response->assertRedirect('/admin/members');
    }

    public function test_admin_can_delete_a_member(): void
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin, 'admin')->delete("/admin/members/{$user->id}");

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);

        $response->assertRedirect('/admin/members');
    }
}
