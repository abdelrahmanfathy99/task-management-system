<?php

namespace App\Http\Controllers\Api\V1;

use App\Services\Api\V1\RegisterUserService;
use App\Services\Api\V1\AuthenticateUserService;
use App\Services\Api\V1\LogoutUserService;
use App\DTOs\Api\V1\AuthenticateUserDTO;
use App\DTOs\Api\V1\RegisterUserDTO;
use App\Http\Requests\Api\V1\AuthenticateUserRequest;
use App\Http\Requests\Api\V1\RegisterUserRequest;
use App\Http\Resources\Api\V1\RegisterUserResource;
use App\Http\Resources\Api\V1\LoginUserResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserAuthController extends Controller
{
    public function register(RegisterUserRequest $request, RegisterUserService $action): JsonResponse
    {
        $validated = $request->validated();

        $dto = new RegisterUserDTO(
            $validated['name'],
            $validated['email'],
            $validated['password']
        );

        $result = $action->execute($dto);

        return (new RegisterUserResource($result))->response()->setStatusCode(201);
    }

    public function login(AuthenticateUserRequest $request, AuthenticateUserService $action): JsonResponse
    {
        $validated = $request->validated();

        $dto = new AuthenticateUserDTO($validated['email'], $validated['password']);
        $result = $action->execute($dto);

        return (new LoginUserResource($result))->response();
    }

    public function logout(Request $request, LogoutUserService $action): JsonResponse
    {
        $userId = (int) $request->user()->id;

        $action->execute($userId);

        return response()->json([
            'message' => 'Logout successful',
        ]);
    }
}
