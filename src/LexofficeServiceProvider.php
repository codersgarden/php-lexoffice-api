<?php

namespace Codersgarden\PhpLexofficeApi;

use Illuminate\Support\ServiceProvider;
use Codersgarden\PhpLexofficeApi\SomeMainClass;

class LexofficeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('lexoffice-api', function ($app) {
            return new SomeMainClass();
        });
    }

    public function boot()
    {
        // Perform any actions necessary during bootstrapping
    }
}
