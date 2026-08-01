<?php

namespace App\Providers;

use App\Repositories\Contracts\AuthTokenGenerator;
use App\Repositories\Eloquent\SanctumAuthTokenGenerator;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthTokenGenerator::class, SanctumAuthTokenGenerator::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
    }
}
