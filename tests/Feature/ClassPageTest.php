<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClassPageTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_classes_page_loads_successfully(): void
    {
        $response = $this->get('/classes');

        $response->assertStatus(200);
    }
}
