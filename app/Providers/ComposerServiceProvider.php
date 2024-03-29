<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // always pass information about the logged in user
        view()->composer('*',
            'App\Http\ViewComposers\UserInfoComposer'
        );

        // always pass information about the actual environment
        view()->composer('*',
            'App\Http\ViewComposers\EnvironmentComposer'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
