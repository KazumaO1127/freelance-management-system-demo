<?php

namespace App\Providers;

use App\Domain\Repositories\ProjectRepositoryInterface;
use App\Infrastructure\Repositories\EloquentProjectRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProjectRepositoryInterface::class, EloquentProjectRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
