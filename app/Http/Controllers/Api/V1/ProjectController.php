<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Api\V1\CreateProjectDTO;
use App\DTOs\Api\V1\ListProjectsDTO;
use App\DTOs\Api\V1\UpdateProjectDTO;
use App\DTOs\Pagination\PaginationParamsDTO;
use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CreateProjectRequest;
use App\Http\Requests\Api\V1\ListProjectsRequest;
use App\Http\Requests\Api\V1\UpdateProjectRequest;
use App\Http\Resources\Api\V1\CreateProjectResource;
use App\Http\Resources\Api\V1\ProjectCollectionResource;
use App\Http\Resources\Api\V1\ShowProjectResource;
use App\Http\Resources\Api\V1\UpdateProjectResource;
use App\Services\Api\V1\CreateProjectService;
use App\Services\Api\V1\DeleteProjectService;
use App\Services\Api\V1\ListProjectsService;
use App\Services\Api\V1\UpdateProjectService;
use App\Services\Api\V1\ViewProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ProjectController extends Controller
{
    #[OA\Get(
        path: '/projects',
        summary: 'List projects',
        tags: ['Projects'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'status', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['active', 'completed', 'archived'])),
            new OA\Parameter(name: 'cursor', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100, example: 15)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Projects retrieved successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function index(ListProjectsRequest $request, ListProjectsService $action): JsonResponse
    {
        $validated = $request->validated();

        $dto = new ListProjectsDTO(
            userId: (int) $request->user()->id,
            pagination: new PaginationParamsDTO(
                cursor: $validated['cursor'] ?? null,
                perPage: (int) ($validated['per_page'] ?? 15)
            ),
            search: $validated['search'] ?? null,
            status: isset($validated['status'])
                ? ProjectStatus::from($validated['status'])
                : null
        );

        $result = $action->execute($dto);

        return (new ProjectCollectionResource($result))->response();
    }

    #[OA\Post(
        path: '/projects',
        summary: 'Create a project',
        tags: ['Projects'],
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Website Redesign'),
                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Rebuild the marketing site'),
                    new OA\Property(property: 'status', type: 'string', enum: ['active', 'completed', 'archived'], example: 'active'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Project created successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(CreateProjectRequest $request, CreateProjectService $action): JsonResponse
    {
        $validated = $request->validated();

        $dto = new CreateProjectDTO(
            userId: (int) $request->user()->id,
            name: $validated['name'],
            description: $validated['description'] ?? null,
            status: ProjectStatus::from($validated['status'] ?? ProjectStatus::Active->value)
        );

        $result = $action->execute($dto);

        return (new CreateProjectResource($result))->response()->setStatusCode(201);
    }

    #[OA\Get(
        path: '/projects/{project}',
        summary: 'Get a project',
        tags: ['Projects'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'project', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Project retrieved successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Project not found'),
        ]
    )]
    public function show(Request $request, int $project, ViewProjectService $action): JsonResponse
    {
        $userId = (int) $request->user()->id;

        $result = $action->execute($project, $userId);

        return (new ShowProjectResource($result))->response();
    }

    #[OA\Put(
        path: '/projects/{project}',
        summary: 'Update a project',
        tags: ['Projects'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'project', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Website Redesign'),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                    new OA\Property(property: 'status', type: 'string', enum: ['active', 'completed', 'archived']),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Project updated successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Project not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(UpdateProjectRequest $request, int $project, UpdateProjectService $action): JsonResponse
    {
        $validated = $request->validated();

        $dto = new UpdateProjectDTO(
            projectId: $project,
            userId: (int) $request->user()->id,
            name: $validated['name'] ?? null,
            description: $validated['description'] ?? null,
            status: $validated['status'] ?? null
        );

        $result = $action->execute($dto);

        return (new UpdateProjectResource($result))->response();
    }

    #[OA\Delete(
        path: '/projects/{project}',
        summary: 'Delete a project',
        tags: ['Projects'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'project', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Project deleted successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Project not found'),
        ]
    )]
    public function destroy(Request $request, int $project, DeleteProjectService $action): JsonResponse
    {
        $userId = (int) $request->user()->id;

        $action->execute($project, $userId);

        return response()->json([
            'message' => 'Project deleted successfully',
        ]);
    }
}
