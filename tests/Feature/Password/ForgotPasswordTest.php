<?php

use App\Models\User;

describe('Forgot Password', function () {
    it('sends reset link successfully', function () {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/auth/forgot-password', [
            'email' => $user->email
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ]);
    });

    it('fails with non-existent email', function () {
        $response = $this->postJson('/api/v1/auth/forgot-password', [
            'email' => 'nonexistent@example.com'
        ]);

        $response->assertStatus(200);
    });

    it('respect rate limiting', function () {
        $user = User::factory()->create();

        for ($i = 0; $i < 7; $i++) {
            $response = $this->postJson('/api/v1/auth/forgot-password', [
                'email' => $user->email
            ]);
        }

        $response->assertStatus(429);
    });
});
