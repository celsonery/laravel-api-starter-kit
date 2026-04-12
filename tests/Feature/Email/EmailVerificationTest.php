<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Email Verification', function () {
    it('verifies email successfully with valid link', function () {
        Event::fake();

        $user = User::factory()->create(['email_verified_at' => null]);
        $token = $user->createToken('test-token')->plainTextToken;

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson($verificationUrl);

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);

        $this->assertNotNull($user->fresh()->email_verified_at);
        Event::assertDispatched(Verified::class);
    });

    it('retuns success if email is already verified', function () {
        $user = User::factory()->create([
            'email_verified_at' => now()
        ]);
        $token = $user->createToken('test-token')->plainTextToken;

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson($verificationUrl);

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);
    });

    it('fails verification without authentication', function (): void {
        $user = User::factory()->create(['email_verified_at' => null]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->postJson($verificationUrl);

        $response->assertStatus(401);
    });

    it('fails verification with invalid signature', function (): void {
        $user = User::factory()->create(['email_verified_at' => null]);
        $token = $user->createToken('test-token')->plainTextToken;

        // Invalid URL without signature
        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson(sprintf('/api/v1/auth/email/verify/%d/invalid-hash', $user->id));

        $response->assertStatus(403);
    });
});

describe('Resend Verification Email', function () {
    it('resends verification email successfully', function (): void {
        $user = User::factory()->create(['email_verified_at' => null]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/auth/email/resend', [
                'email' => $user->email,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['message']);
    });

    it('fails to resend if email is already verified', function (): void {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/auth/email/resend', [
                'email' => $user->email,
            ]);

        $response->assertStatus(400)
            ->assertJsonStructure(['message']);
    });

    it('fails with invalid email', function (): void {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/auth/email/resend', [
                'email' => 'nonexistent@example.com',
            ]);

        $response->assertStatus(422);
    });

    it('requires authentication', function (): void {
        $user = User::factory()->create(['email_verified_at' => null]);

        $response = $this->postJson('/api/v1/auth/email/resend', [
            'email' => $user->email,
        ]);

        $response->assertStatus(401);
    });

    it('respects rate limiting', function (): void {
        $user = User::factory()->create(['email_verified_at' => null]);
        $token = $user->createToken('test-token')->plainTextToken;

        // Make 7 requests (limit is 6 per minute)
        for ($i = 0; $i < 7; $i++) {
            $response = $this->withHeader('Authorization', 'Bearer '.$token)
                ->postJson('/api/v1/auth/email/resend', [
                    'email' => $user->email,
                ]);
        }

        // Last request should be rate limited
        $response->assertStatus(429);
    });
});
