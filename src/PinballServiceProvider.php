<?php

namespace Vio\Pinball;

use Illuminate\Support\ServiceProvider;
use Vio\Pinball\Console\InstallPinball;

class PinballServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'vio');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'vio');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/pinball.php', 'pinball');

        // Register the service the package provides.
        $this->app->singleton('pinball', function ($app) {
            return new Pinball;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['pinball'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/pinball.php' => config_path('pinball.php'),
        ], 'pinball.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/vio'),
        ], 'pinball.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/vio'),
        ], 'pinball.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/vio'),
        ], 'pinball.views');*/

        // Registering package commands.
        $this->commands([
            InstallPinball::class
        ]);

    }
}
