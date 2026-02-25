<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            'App\Repositories\SliderRepositoryInterface',
            'App\Repositories\SliderRepository'
        );

        $this->app->bind(
            'App\Repositories\EventCategoryRepositoryInterface',
            'App\Repositories\EventCategoryRepository'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       Schema::defaultStringLength(125);

    }
}

