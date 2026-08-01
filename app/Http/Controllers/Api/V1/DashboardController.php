<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\DashboardStatsResource;
use App\Services\Api\V1\GetDashboardStatsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request, GetDashboardStatsService $action): JsonResponse
    {
        $result = $action->execute((int) $request->user()->id);

        return (new DashboardStatsResource($result))->response();
    }
}
