<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\DashboardStatsResource;
use App\Services\Api\V1\GetDashboardStatsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DashboardController extends Controller
{
    #[OA\Get(
        path: '/dashboard',
        summary: 'Get dashboard statistics',
        tags: ['Dashboard'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Dashboard stats retrieved successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function __invoke(Request $request, GetDashboardStatsService $action): JsonResponse
    {
        $result = $action->execute((int) $request->user()->id);

        return (new DashboardStatsResource($result))->response();
    }
}
