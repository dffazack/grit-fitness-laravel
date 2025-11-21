<?php

test('the application returns a successful response', function () {
    $this->seed = true; // Ensure seeding is enabled for this test
    $response = $this->withoutExceptionHandling()->get('/');

    $response->assertStatus(200);
});