<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Api\V1\AuthenticateUserDTO;
use App\DTOs\Api\V1\RegisterUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AuthenticateUserRequest;
use App\Http\Requests\Api\V1\RegisterUserRequest;
use App\Http\Resources\Api\V1\LoginUserResource;
use App\Http\Resources\Api\V1\RegisterUserResource;
use App\Services\Api\V1\AuthenticateUserService;
use App\Services\Api\V1\LogoutUserService;
use App\Services\Api\V1\RegisterUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class UserAuthController extends Controller
{
    #[OA\Post(
        path: '/auth/register',
        summary: 'Register a new user',
        tags: ['Auth'],
        security: [],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password', 'password_confirmation'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
                    new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'password123'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Registration successful'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
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

    #[OA\Post(
        path: '/auth/login',
        summary: 'Login a user',
        tags: ['Auth'],
        security: [],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Login successful'),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 500, description: 'Invalid credentials'),
        ]
    )]
    public function login(AuthenticateUserRequest $request, AuthenticateUserService $action): JsonResponse
    {
        $validated = $request->validated();

        $dto = new AuthenticateUserDTO($validated['email'], $validated['password']);
        $result = $action->execute($dto);

        return (new LoginUserResource($result))->response();
    }

    #[OA\Post(
        path: '/auth/logout',
        summary: 'Logout the authenticated user',
        tags: ['Auth'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Logout successful'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function logout(Request $request, LogoutUserService $action): JsonResponse
    {
        $userId = (int) $request->user()->id;

        $action->execute($userId);

        return response()->json([
            'message' => 'Logout successful',
        ]);
    }
}
