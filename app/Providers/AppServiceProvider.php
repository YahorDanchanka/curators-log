<?php

namespace App\Providers;

use App\Services\GroupCompositionService;
use App\Services\LeadershipService;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;
use PhpOffice\PhpWord\Settings;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Sanctum::ignoreMigrations();

        $this->app->singleton(LeadershipService::class, fn() => new LeadershipService());
        $this->app->singleton(GroupCompositionService::class, fn() => new GroupCompositionService());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
    }
}
