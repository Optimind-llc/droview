<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FlightServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            \App\Repositories\Frontend\Flight\FlightContract::class,
            \App\Repositories\Frontend\Flight\EloquentFlightRepository::class
        );

        $this->app->bind(
            \App\Repositories\Frontend\Plan\PlanContract::class,
            \App\Repositories\Frontend\Plan\EloquentPlanRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Flight\FlightContract::class,
            \App\Repositories\Backend\Flight\EloquentFlightRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Plan\PlanContract::class,
            \App\Repositories\Backend\Plan\EloquentPlanRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Type\TypeContract::class,
            \App\Repositories\Backend\Type\EloquentTypeRepository::class
        );

        $this->app->bind(
            \App\Repositories\Backend\Place\PlaceContract::class,
            \App\Repositories\Backend\Place\EloquentPlaceRepository::class
        );
    }
}
