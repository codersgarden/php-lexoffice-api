<?php

namespace Codersgarden\PhpLexofficeApi;

use Illuminate\Support\ServiceProvider;

class LexofficeServiceProvider extends ServiceProvider
{
    public function register()
    {

        // Register the Contact class
        $this->app->singleton('contact', function ($app) {
            return new \Codersgarden\PhpLexofficeApi\LexofficeContactManager();
        });

        $this->app->singleton('lexoffice-articles', function ($app) {
            return new \Codersgarden\PhpLexofficeApi\LexofficeArticleManager();
        });

        $this->app->singleton('lexoffice-countries', function ($app) {
            return new \Codersgarden\PhpLexofficeApi\LexofficeCountryManager();
        });

        $this->app->singleton('lexoffice-credit-notes', function ($app) {
            return new \Codersgarden\PhpLexofficeApi\LexofficeCreditNotesManager();
        });

        $this->app->singleton('lexoffice-delivery-notes', function ($app) {
            return new \Codersgarden\PhpLexofficeApi\LexofficeDeliveryNotesManager();
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
