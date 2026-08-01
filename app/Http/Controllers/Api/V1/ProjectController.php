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

class ProjectController extends Controller
{
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

    public function show(Request $request, int $project, ViewProjectService $action): JsonResponse
    {
        $userId = (int) $request->user()->id;

        $result = $action->execute($project, $userId);

        return (new ShowProjectResource($result))->response();
    }

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

    public function destroy(Request $request, int $project, DeleteProjectService $action): JsonResponse
    {
        $userId = (int) $request->user()->id;

        $action->execute($project, $userId);

        return response()->json([
            'message' => 'Project deleted successfully',
        ]);
    }
}
