<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Indicates if the database should be seeded.
     *
     * @var bool
     */
    protected $seed = true;
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->withoutExceptionHandling()->get('/');

        $response->assertStatus(200);
    }
}
