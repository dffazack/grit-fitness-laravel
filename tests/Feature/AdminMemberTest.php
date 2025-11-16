<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_members_page(): void
    {
        $admin = Admin::factory()->create();

        $response = $this->actingAs($admin, 'admin')->get('/admin/members');

        $response->assertStatus(200);
    }

    public function test_admin_can_delete_a_member(): void
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin, 'admin')->delete(route('admin.members.destroy', $user));

        $this->assertSoftDeleted($user);

        $response->assertRedirect(route('admin.members.index'));
    }
}
