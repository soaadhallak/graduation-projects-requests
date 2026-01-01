<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\ForgotPasswordAction;
use App\Actions\Auth\ResetPasswordAction;
use App\Data\PasswordData;
use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Http\JsonResponse;


class PasswordResetController extends Controller
{
    public function sendResetLink(ForgotPasswordRequest $request,ForgotPasswordAction $forgotPasswordAction):JsonResponse
    {
        $message=$forgotPasswordAction->execute(PasswordData::from($request->validated()));
        return response()->json(['message' => $message]);
    }

    public function ResetPssword(ResetPasswordRequest $request,ResetPasswordAction $resetPasswordAction):JsonResponse
    {
        $message=$resetPasswordAction->execute(PasswordData::from($request->validated()));
        return response()->json(['message' => $message]);
    }
}
