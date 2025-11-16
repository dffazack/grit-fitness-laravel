<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\MembershipPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMembershipPackageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(Admin::factory()->create(), 'admin');
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    public function test_admin_can_view_packages_index_page(): void
    {
        $response = $this->get(route('admin.memberships.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_store_a_new_package(): void
    {
        $packageData = [
            'type' => 'regular',
            'name' => 'Gold Package',
            'price' => 500000,
            'duration_months' => 3,
            'features' => 'Feature 1, Feature 2',
            'description' => 'A great package.',
            'is_active' => 'on',
        ];

        $response = $this->post(route('admin.memberships.store'), $packageData);

        $response->assertRedirect(route('admin.memberships.index'));
        $this->assertDatabaseHas('membership_packages', ['name' => 'Gold Package']);
    }

    public function test_admin_can_update_a_package(): void
    {
        $package = MembershipPackage::factory()->create();

        $updateData = [
            'type' => 'student',
            'name' => 'Updated Gold Package',
            'price' => 600000,
            'duration_months' => 4,
            'features' => 'Feature 1, Feature 2, Feature 3',
            'description' => 'An updated great package.',
            'is_active' => 'on',
        ];

        $response = $this->put(route('admin.memberships.update', $package), $updateData);

        $response->assertRedirect(route('admin.memberships.index'));
        $this->assertDatabaseHas('membership_packages', ['name' => 'Updated Gold Package', 'duration_months' => 4]);
    }

    public function test_admin_can_delete_a_package(): void
    {
        $package = MembershipPackage::factory()->create();

        $response = $this->delete(route('admin.memberships.destroy', $package));

        $response->assertRedirect(route('admin.memberships.index'));
        $this->assertDatabaseMissing('membership_packages', ['id' => $package->id]);
    }
}
