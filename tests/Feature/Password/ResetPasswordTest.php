<?php

use App\Models\User;

describe('Reset Password', function () {
    it('resets password successfully with valid token', function () {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $response = $this->postJson('/api/v1/auth/reset-password', [
            'email' => $user->email,
            'token' => $token,
            'password' => 'newPassword123',
            'password_confirmation' => 'newPassword123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ]);

        $this->assertTrue(Hash::check('newPassword123', $user->fresh()->password));

        $this->assertDatabaseCount('personal_access_tokens', 0);
    });

    it('fails with invalid token', function () {
        $user = User::factory()->create();

        $response = $this->postJson('/api/v1/auth/reset-password', [
            'email' => $user->email,
            'token' => 'invalid-token',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message'
            ]);
    });

    it('fails with mismatched passwords', function () {
        $user = User::factory()->create();
        $token = Password::createToken($user);

        $response = $this->postJson('/api/v1/auth/reset-password', [
            'email' => $user->email,
            'token' => $token,
            'password' => '12345678',
            'password_confirmation' => '87654321'
        ]);

        $response->assertStatus(422);
    });

    it('fails with non-existent email', function () {
        $response = $this->postJson('/api/v1/auth/reset-password', [
            'email' => 'nonexistent@example.com',
            'token' => 'some-token',
            'password' => '12345678',
            'password_confirmation' => '12345678'
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors' => ['email']]);
    });
});
