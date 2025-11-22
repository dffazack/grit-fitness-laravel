<?php

test('classes page loads successfully', function () {
    $this->seed = true;
    $response = $this->withoutExceptionHandling()->get('/classes');

    $response->assertStatus(200);
});