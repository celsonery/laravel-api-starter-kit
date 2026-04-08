<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        if (!User::create($request->validated())) {
//        $user->sendEmailVerificationNotification();
            return response()->json(['message' => 'Error creating user!'], 401);
        }

        return response()->json(['message' => 'User created successfully!'], 201);
    }
}
