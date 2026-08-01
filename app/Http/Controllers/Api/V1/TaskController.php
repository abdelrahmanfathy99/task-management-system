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

class TaskController extends Controller
{
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

    public function destroy(Request $request, int $project, int $task, DeleteTaskService $action): JsonResponse
    {
        $userId = (int) $request->user()->id;

        $action->execute($task, $project, $userId);

        return response()->json([
            'message' => 'Task deleted successfully',
        ]);
    }
}
