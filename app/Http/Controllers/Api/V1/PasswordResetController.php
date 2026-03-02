<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\ForgotPasswordRequest;
use App\Http\Requests\V1\Auth\ResetPasswordRequest;
use App\Services\PasswordResetService;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;

class PasswordResetController extends Controller
{
    use ApiResponseTrait;

    protected PasswordResetService $passwordResetService;

    public function __construct(PasswordResetService $passwordResetService)
    {
        $this->passwordResetService = $passwordResetService;
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $this->passwordResetService->sendResetToken($request->validated('email'));

            return $this->successResponse(
                [],
                'If your email exists, a password reset token has been sent (Check laravel.log).'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to process request', ['server' => [$e->getMessage()]], 500);
        }
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $this->passwordResetService->resetPassword(
                $request->validated('email'),
                $request->validated('token'),
                $request->validated('password')
            );

            return $this->successResponse([], 'Password has been reset successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Invalid request', ['token' => [$e->getMessage()]], 400);
        }
    }
}
