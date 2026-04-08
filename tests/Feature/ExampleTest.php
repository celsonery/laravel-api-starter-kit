<?php

namespace Tests\Feature;

it('test the application returns a successful', function () {
    $response = $this->get('/api/v1/health');

    $response->assertStatus(200);
});
