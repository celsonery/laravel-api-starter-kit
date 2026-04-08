<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticatonController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::query()->where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user['access_token'] = $user->createToken('access_token')->plainTextToken;

        return response()->json(['user' => $user], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user->currentAccessToken()->delete()) {
            return response()->json(['message' => 'User token not rovoked!'], 401);
        }

        return response()->json(['message' => 'User logged out successfully!'], 200);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json(['user' => $request->user()]);
    }
}
