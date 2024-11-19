<?php

namespace Codersgarden\PhpLexofficeApi;

use Illuminate\Support\ServiceProvider;

class LexofficeServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register the first class
        $this->app->singleton('lexoffice-api', function ($app) {
            return new \Codersgarden\PhpLexofficeApi\SomeMainClass();
        });

        // Register the Contact class
        $this->app->singleton('contact', function ($app) {
            return new \Codersgarden\PhpLexofficeApi\Contact();
        });

        $this->mergeConfigFrom(__DIR__ . '/../config/lexoffice.php', 'lexoffice');
    }

    public function boot()
    {
        // Publish the config file
        $this->publishes([
            __DIR__ . '/../config/lexoffice.php' => config_path('lexoffice.php'),
        ], 'config');
    }
}
