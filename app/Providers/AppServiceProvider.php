<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->bind(
            \App\Repositories\StockOpname\StockOpnameRepository::class,
            \App\Repositories\StockOpname\StockOpnameRepositoryImplement::class
        );
        $this->app->bind(
            \App\Services\StockOpname\StockOpnameService::class,
            \App\Services\StockOpname\StockOpnameServiceImplement::class
        );

        $this->app->bind(
            \App\Repositories\RackLocation\RackLocationRepository::class,
            \App\Repositories\RackLocation\RackLocationRepositoryImplement::class
        );
        $this->app->bind(
            \App\Services\RackLocation\RackLocationService::class,
            \App\Services\RackLocation\RackLocationServiceImplement::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
