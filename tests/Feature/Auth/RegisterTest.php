<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Register', function () {

    it('Register a new user successfully', function () {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Pass@..w/*123',
            'password_confirmation' => 'Pass@..w/*123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    });

    it('fails registration without a letter in password', function () {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
            ]);
    });

    it('fails registration without a number in password', function () {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'abcdefgh',
            'password_confirmation' => 'abcdefgh',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
            ]);
    });

    it('fails registration with password compromissed', function () {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password',
            'password_confirmation' => 'Password',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
            ]);
    });

    it('fails registration with blank data', function () {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['name', 'email', 'password'],
            ])
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    });

    it('fail registration without name', function () {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['name'],
            ])
            ->assertJsonValidationErrors(['name']);
    });

    it('fail registration without email', function () {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'test',
            'email' => '',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['email'],
            ])
            ->assertJsonValidationErrors(['email']);
    });

    it('fail registration with invalid email', function () {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'test',
            'email' => 'test@',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['email'],
            ])
            ->assertJsonValidationErrors(['email']);
    });

    it('fail registration without password', function () {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '12345678',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['password'],
            ])
            ->assertJsonValidationErrors(['password']);
    });

    it('fail registration without password_confirmation', function () {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => '12345678',
            'password_confirmation' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['password'],
            ])
            ->assertJsonValidationErrors(['password']);
    });

    it('fail registration without min size password', function () {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => '12345',
            'password_confirmation' => '12345',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['password'],
            ])
            ->assertJsonValidationErrors(['password']);
    });

    it('fail registration with too length name', function () {
        $name = str_repeat('A', 512);

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => $name,
            'email' => 'test@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['name'],
            ])
            ->assertJsonValidationErrors(['name']);
    });

    it('fail registration with too length password', function () {
        $pass = str_repeat('A', 1024);

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => $pass,
            'password_confirmation' => $pass,
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => ['password'],
            ])
            ->assertJsonValidationErrors(['password']);
    });

    it('fail registration with duplicate email', function () {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'test',
            'email' => 'existing@example.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ]);

        $response->assertStatus(422);
    });

    it('fail registration with weak password', function () {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'test',
            'email' => 'existing@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(422);
    });
});
