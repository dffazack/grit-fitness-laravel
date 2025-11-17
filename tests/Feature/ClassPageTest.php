<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClassPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Indicates if the database should be seeded.
     *
     * @var bool
     */
    protected $seed = true;
    /**
     * A basic feature test example.
     */
    public function test_classes_page_loads_successfully(): void
    {
        $response = $this->withoutExceptionHandling()->get('/classes');

        $response->assertStatus(200);
    }
}
