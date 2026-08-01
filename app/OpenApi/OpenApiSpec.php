<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Task Management System API',
    description: 'API documentation for the Task Management System'
)]
#[OA\Server(
    url: '/api/v1',
    description: 'API V1'
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    description: 'Login or register to get a token, then click Authorize and enter: Bearer {token}',
    name: 'Authorization',
    in: 'header',
    scheme: 'bearer',
    bearerFormat: 'Sanctum'
)]
class OpenApiSpec
{
}
