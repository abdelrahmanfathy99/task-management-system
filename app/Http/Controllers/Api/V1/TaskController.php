<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\Api\V1\CreateTaskDTO;
use App\DTOs\Api\V1\ListTasksDTO;
use App\DTOs\Api\V1\UpdateTaskDTO;
use App\DTOs\Pagination\PaginationParamsDTO;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CreateTaskRequest;
use App\Http\Requests\Api\V1\ListTasksRequest;
use App\Http\Requests\Api\V1\UpdateTaskRequest;
use App\Http\Resources\Api\V1\CreateTaskResource;
use App\Http\Resources\Api\V1\TaskCollectionResource;
use App\Http\Resources\Api\V1\UpdateTaskResource;
use App\Services\Api\V1\CreateTaskService;
use App\Services\Api\V1\DeleteTaskService;
use App\Services\Api\V1\ListTasksService;
use App\Services\Api\V1\UpdateTaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class TaskController extends Controller
{
    #[OA\Get(
        path: '/projects/{project}/tasks',
        summary: 'List tasks for a project',
        tags: ['Tasks'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'project', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'status', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['todo', 'in_progress', 'done'])),
            new OA\Parameter(name: 'priority', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['low', 'medium', 'high'])),
            new OA\Parameter(name: 'cursor', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100, example: 15)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Tasks retrieved successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Project not found'),
        ]
    )]
    public function index(ListTasksRequest $request, int $project, ListTasksService $action): JsonResponse
    {
        $validated = $request->validated();

        $dto = new ListTasksDTO(
            projectId: $project,
            userId: (int) $request->user()->id,
            pagination: new PaginationParamsDTO(
                cursor: $validated['cursor'] ?? null,
                perPage: (int) ($validated['per_page'] ?? 15)
            ),
            search: $validated['search'] ?? null,
            status: isset($validated['status'])
                ? TaskStatus::from($validated['status'])
                : null,
            priority: isset($validated['priority'])
                ? TaskPriority::from($validated['priority'])
                : null
        );

        $result = $action->execute($dto);

        return (new TaskCollectionResource($result))->response();
    }

    #[OA\Post(
        path: '/projects/{project}/tasks',
        summary: 'Create a task',
        tags: ['Tasks'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'project', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title'],
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Implement login page'),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                    new OA\Property(property: 'priority', type: 'string', enum: ['low', 'medium', 'high'], example: 'medium'),
                    new OA\Property(property: 'status', type: 'string', enum: ['todo', 'in_progress', 'done'], example: 'todo'),
                    new OA\Property(property: 'due_date', type: 'string', format: 'date', nullable: true, example: '2026-08-15'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Task created successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Project not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(CreateTaskRequest $request, int $project, CreateTaskService $action): JsonResponse
    {
        $validated = $request->validated();

        $dto = new CreateTaskDTO(
            projectId: $project,
            userId: (int) $request->user()->id,
            title: $validated['title'],
            description: $validated['description'] ?? null,
            priority: TaskPriority::from($validated['priority'] ?? TaskPriority::Medium->value),
            status: TaskStatus::from($validated['status'] ?? TaskStatus::Todo->value),
            dueDate: $validated['due_date'] ?? null
        );

        $result = $action->execute($dto);

        return (new CreateTaskResource($result))->response()->setStatusCode(201);
    }

    #[OA\Put(
        path: '/projects/{project}/tasks/{task}',
        summary: 'Update a task',
        tags: ['Tasks'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'project', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'task', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'title', type: 'string'),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                    new OA\Property(property: 'priority', type: 'string', enum: ['low', 'medium', 'high']),
                    new OA\Property(property: 'status', type: 'string', enum: ['todo', 'in_progress', 'done']),
                    new OA\Property(property: 'due_date', type: 'string', format: 'date', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Task updated successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Task or project not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(UpdateTaskRequest $request, int $project, int $task, UpdateTaskService $action): JsonResponse
    {
        $validated = $request->validated();

        $dto = new UpdateTaskDTO(
            taskId: $task,
            projectId: $project,
            userId: (int) $request->user()->id,
            title: $validated['title'] ?? null,
            description: $validated['description'] ?? null,
            priority: $validated['priority'] ?? null,
            status: $validated['status'] ?? null,
            dueDate: $validated['due_date'] ?? null,
            descriptionProvided: array_key_exists('description', $validated),
            dueDateProvided: array_key_exists('due_date', $validated),
        );

        $result = $action->execute($dto);

        return (new UpdateTaskResource($result))->response();
    }

    #[OA\Delete(
        path: '/projects/{project}/tasks/{task}',
        summary: 'Delete a task',
        tags: ['Tasks'],
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'project', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'task', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Task deleted successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Task or project not found'),
        ]
    )]
    public function destroy(Request $request, int $project, int $task, DeleteTaskService $action): JsonResponse
    {
        $userId = (int) $request->user()->id;

        $action->execute($task, $project, $userId);

        return response()->json([
            'message' => 'Task deleted successfully',
        ]);
    }
}
