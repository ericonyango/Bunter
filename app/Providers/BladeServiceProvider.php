<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Blade::if('error',function ($name,$errors){
            return $errors->has($name);
        });
        //if we are on this route custom blade directive
        Blade::if('isroute',function ($routeName){
            return str_contains(\Route::currentRouteName(), $routeName);
        });

    }
}
