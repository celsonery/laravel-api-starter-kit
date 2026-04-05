<?php

namespace Tests\Feature;

it('test the application returns a successful', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
