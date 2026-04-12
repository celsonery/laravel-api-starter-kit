<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if (!$user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email not verified!'], 403);
        }

        $deviceName = $request->device_name ?? 'api';
        $user->tokens()->where('name', $deviceName)->delete();

        $access_token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'access_token' => $access_token,
            'token_type' => 'Bearer',
            'user' => $user->only(['id', 'name', 'email', 'email_verified_at', 'created_at']),
            ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user->currentAccessToken()->delete()) {
            return response()->json(['message' => 'User token not revoked!'], 403);
        }

        return response()->json(['message' => 'User logged out successfully!']);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json(['user' => $request->user()->only(['id', 'name', 'email', 'email_verified_at', 'created_at'])]);
    }
}
