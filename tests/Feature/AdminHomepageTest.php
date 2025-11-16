<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminHomepageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(Admin::factory()->create(), 'admin');
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    public function test_admin_can_view_homepage_edit_page(): void
    {
        $response = $this->get(route('admin.homepage.edit'));
        $response->assertStatus(200);
    }

    public function test_admin_can_update_hero_section(): void
    {
        $data = [
            'title' => 'New Hero Title',
            'subtitle' => 'New Hero Subtitle',
            'image' => 'http://example.com/image.jpg',
        ];

        $response = $this->put(route('admin.homepage.hero'), $data);

        $response->assertRedirect(route('admin.homepage.edit') . '#hero');
        $this->assertDatabaseHas('homepage_contents', [
            'section' => 'hero',
            'content' => json_encode($data),
        ]);
    }

    public function test_admin_can_update_stats_section(): void
    {
        $data = [
            'stats' => [
                ['value' => '100+', 'label' => 'Members'],
                ['value' => '50+', 'label' => 'Classes'],
                ['value' => '20+', 'label' => 'Trainers'],
                ['value' => '10+', 'label' => 'Years'],
            ],
        ];

        $response = $this->put(route('admin.homepage.stats'), $data);

        $response->assertRedirect(route('admin.homepage.edit') . '#stats');
        $this->assertDatabaseHas('homepage_contents', [
            'section' => 'stats',
            'content' => json_encode($data['stats']),
        ]);
    }
}
