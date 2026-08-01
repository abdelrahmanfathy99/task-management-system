<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardStatsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'message' => 'Dashboard stats retrieved successfully',
            'data' => [
                'total_projects' => $this->resource->totalProjects,
                'active_projects' => $this->resource->activeProjects,
                'total_tasks' => $this->resource->totalTasks,
                'completed_tasks' => $this->resource->completedTasks,
                'pending_tasks' => $this->resource->pendingTasks,
                'overdue_tasks' => $this->resource->overdueTasks,
            ],
        ];
    }
}
