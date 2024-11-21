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

        $this->app->singleton('lexoffice-article', function ($app) {
            return new \Codersgarden\PhpLexofficeApi\LexofficeArticleManager();
        });

        $this->app->singleton('lexoffice-country', function ($app) {
            return new \Codersgarden\PhpLexofficeApi\LexofficeCountryManager();
        });

        $this->app->singleton('lexoffice-credit-note', function ($app) {
            return new \Codersgarden\PhpLexofficeApi\LexofficeCreditNoteManager();
        });

        $this->app->singleton('lexoffice-delivery-note', function ($app) {
            return new \Codersgarden\PhpLexofficeApi\LexofficeDeliveryNotesManager();
        });

        $this->app->singleton('lexoffice-file', function ($app) {
            return new \Codersgarden\PhpLexofficeApi\LexofficeFileManager();
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
