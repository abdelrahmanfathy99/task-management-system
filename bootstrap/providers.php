<?php

use App\Providers\AppServiceProvider;
use App\Providers\RepositoryServiceProvider;
use L5Swagger\L5SwaggerServiceProvider;

return [
    AppServiceProvider::class,
    RepositoryServiceProvider::class,
    L5SwaggerServiceProvider::class,
];
