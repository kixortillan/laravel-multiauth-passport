<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Lib\Verifier\VerifierInterface::class, \App\Lib\Verifier\TokenVerify::class);
        $this->app->bind(\App\Services\Contracts\AuthServiceInterface::class, \App\Services\AuthService::class);
    }

}
