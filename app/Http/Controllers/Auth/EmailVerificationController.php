<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResendVerificationEmailRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;

class EmailVerificationController extends Controller
{
    public function verify(VerifyEmailRequest $request, int $id, string $hash): JsonResponse
    {
        $user = User::findOrFail($id);

        // Checa se o hash bate com o e-mail do usuário
        if (! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return response()->json(['message' => __('app.verification_link_invalid')], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => __('app.email_already_verified')]);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json(['message' => __('app.email_verified_success')]);
    }

    public function resend(ResendVerificationEmailRequest $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => __('app.email_already_verified')], 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => __('app.email_resend')]);
    }
}
