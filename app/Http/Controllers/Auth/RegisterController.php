<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        if (! $user) {
            return response()->json(['message' => 'Error creating user!'], 401);
        }

        event(new Registered($user));

        return response()->json(['message' => 'User created successfully!'], 201);
    }
}
