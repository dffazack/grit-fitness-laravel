<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminFacilityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(Admin::factory()->create(), 'admin');
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    public function test_admin_can_view_facilities_index_page(): void
    {
        $response = $this->get(route('admin.facilities.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_store_a_new_facility(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('facility.jpg');

        $facilityData = [
            'name' => 'New Sauna',
            'description' => 'A very hot sauna.',
            'image' => $file,
            'is_active' => true,
        ];

        $response = $this->post(route('admin.facilities.store'), $facilityData);

        $this->assertDatabaseHas('facilities', ['name' => 'New Sauna']);
        Storage::disk('public')->assertExists('facilities/' . $file->hashName());
        $response->assertRedirect(route('admin.facilities.index'));
    }

    public function test_admin_can_update_a_facility(): void
    {
        $facility = Facility::factory()->create();
        Storage::fake('public');
        $file = UploadedFile::fake()->image('new_facility.jpg');

        $updateData = [
            'name' => 'Updated Sauna',
            'description' => 'An even hotter sauna.',
            'image' => $file,
            'is_active' => true,
        ];

        $response = $this->put(route('admin.facilities.update', $facility), $updateData);

        $this->assertDatabaseHas('facilities', ['name' => 'Updated Sauna']);
        Storage::disk('public')->assertExists('facilities/' . $file->hashName());
        $response->assertRedirect(route('admin.facilities.index'));
    }

    public function test_admin_can_delete_a_facility(): void
    {
        $facility = Facility::factory()->create();

        $response = $this->delete(route('admin.facilities.destroy', $facility));

        $this->assertDatabaseMissing('facilities', ['id' => $facility->id]);
        $response->assertRedirect(route('admin.facilities.index'));
    }
}