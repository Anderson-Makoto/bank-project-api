<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Interfaces\Services\IUserService',
            'App\Services\UserService'
        );

        $this->app->bind(
            'App\Interfaces\Repositories\IUserRepository',
            'App\Repositories\UserRepository'
        );

        $this->app->bind(
            'App\Interfaces\Services\IDepositService',
            'App\Services\DepositService'
        );

        $this->app->bind(
            'App\Interfaces\Repositories\IDepositRepository',
            'App\Repositories\DepositRepository'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
