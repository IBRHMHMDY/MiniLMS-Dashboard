<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Profile\UpdateProfileRequest;
use App\Http\Resources\V1\UserResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use ApiResponseTrait;

    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->successResponse(
            new UserResource($user),
            'Profile retrieved successfully'
        );
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();

        $user->update($request->validated());

        return $this->successResponse(
            new UserResource($user),
            'Profile updated successfully'
        );
    }
}
