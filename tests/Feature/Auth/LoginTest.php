<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

describe('Login', function () {
    it('login successfully', function () {
        $user = User::factory()->create([
            'password' => Hash::make('12345678'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'user' => ['id', 'name', 'email', 'email_verified_at', 'created_at'],
            ]);
    });

    it('fails login with blank data', function () {
        $user = User::factory()->create([
            'password' => Hash::make('12345678'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertStatus(422);
    });

    it('fails login with invalid data', function () {
        $user = User::factory()->create([
            'password' => Hash::make('12345678'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => '',
        ]);

        $response->assertStatus(422);
    });

    it('fails login with non-existent user', function () {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'nonexistent@example.com',
            'password' => '12345678',
        ]);

        $response->assertStatus(401);
    });

    it('fail registration with invalid email', function () {
        User::factory()->create([
            'password' => Hash::make('12345678'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@',
            'password' => '12345678',
        ]);

        $response->assertStatus(422);
    });

    it('fail registration with too length password', function () {
        $user = User::factory()->create([
            'password' => Hash::make('12345678'),
        ]);

        $pass = str_repeat('A', 1024);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => $pass,
        ]);

        $response->assertStatus(401);
    });
});
