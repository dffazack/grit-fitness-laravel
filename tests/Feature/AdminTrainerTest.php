<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Trainer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTrainerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(Admin::factory()->create(), 'admin');
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    public function test_admin_can_view_trainers_index_page(): void
    {
        $response = $this->get(route('admin.trainers.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_store_a_new_trainer(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('trainer.jpg');

        $trainerData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'specialization' => 'CrossFit',
            'experience' => 10,
            'clients' => '500+',
            'certifications' => '["CF-L1", "CF-L2"]',
            'bio' => 'A dedicated CrossFit coach.',
            'image' => $file,
            'is_active' => true,
        ];

        $response = $this->post(route('admin.trainers.store'), $trainerData);

        $response->assertRedirect(route('admin.trainers.index'));
        $this->assertDatabaseHas('trainers', ['name' => 'John Doe']);
        // Corrected assertion: Use the uploaded file's hash name
        Storage::disk('public')->assertExists('trainers/' . $file->hashName());
    }

    public function test_admin_can_update_a_trainer(): void
    {
        $trainer = Trainer::factory()->create();
        Storage::fake('public');
        $file = UploadedFile::fake()->image('new_trainer.jpg');

        // Corrected data: Added email and fixed experience
        $updateData = [
            'name' => 'Jane Doe',
            'email' => $trainer->email,
            'specialization' => 'Yoga',
            'experience' => 12,
            'clients' => '600+',
            'certifications' => '["RYT-200"]',
            'bio' => 'A passionate Yoga instructor.',
            'image' => $file,
            'is_active' => true,
        ];

        $response = $this->put(route('admin.trainers.update', $trainer), $updateData);

        $response->assertRedirect(route('admin.trainers.index'));
        $this->assertDatabaseHas('trainers', ['name' => 'Jane Doe']);
        Storage::disk('public')->assertExists($trainer->fresh()->image);
    }

    public function test_admin_can_delete_a_trainer(): void
    {
        $trainer = Trainer::factory()->create();

        $response = $this->delete(route('admin.trainers.destroy', $trainer));

        $response->assertRedirect(route('admin.trainers.index'));
        $this->assertSoftDeleted('trainers', ['id' => $trainer->id]);
    }
}