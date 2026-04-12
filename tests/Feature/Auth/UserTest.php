<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('User', function () {
    it('returns authenticated user data', function () {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/auth/user');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email', 'email_verified_at', 'created_at']
            ])
            ->assertJson([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at->toISOString(),
                    'created_at' => $user->created_at->toISOString()
                ]
            ]);
    });

    it('fails without authenticated', function () {
        $response = $this->getJson('/api/v1/auth/user');

        $response->assertStatus(401);
    });
});
