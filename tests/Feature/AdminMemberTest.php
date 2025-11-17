<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use App\Http\Controllers\Admin\MemberController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMemberTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Indicates if the database should be seeded.
     *
     * @var bool
     */
    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_admin_can_view_members_page(): void
    {
        $adminUser = User::factory()->create(['role' => 'admin']);
        $this->actingAs($adminUser);
        $response = $this->get('/admin/members');

        $response->assertStatus(200);
    }

       public function test_admin_can_create_a_new_member_unit_test()
         {
             // 1. Buat instance controller
            $controller = new MemberController();
   
            // 2. Buat mock request dengan data yang valid
            $request = new Request([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);
   
            // 3. Panggil metode 'store' secara langsung
            $response = $controller->store($request);
   
            // 4. Periksa apakah user dibuat di database
            $this->assertDatabaseHas('users', [
                'email' => 'test@example.com',
            ]);
   
            // 5. Periksa apakah respons adalah redirect yang benar
            $this->assertEquals(302, $response->getStatusCode());
            $this->assertEquals(route('admin.members.index'), $response->headers->
     get('Location'));
      }

    public function test_admin_can_edit_a_member_unit_test(): void
    {
        $controller = new MemberController();
        $user = User::factory()->create();

        $request = new Request([
            'name' => 'Updated Name',
            'email' => $user->email,
        ]);

        $response = $controller->update($request, $user->id);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(route('admin.members.index'), $response->headers->get('Location'));
    }

    public function test_admin_can_delete_a_member_unit_test(): void
    {
        $controller = new MemberController();
        $user = User::factory()->create();

        $response = $controller->destroy($user->id);

        $this->assertSoftDeleted('users', [
            'id' => $user->id,
        ]);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(route('admin.members.index'), $response->headers->get('Location'));
    }
}
