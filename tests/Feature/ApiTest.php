<?php

describe('Api Health', function () {
    it('verify if api health', function () {
        $response = $this->get('/api/v1/health');

        $response->assertStatus(200);
    });
});
