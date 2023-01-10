<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Tests\Feature\services\TestService;

class TestServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //app()->bind('TestService',TestService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        app()->bind('TestService',TestService::class);
    }
}
