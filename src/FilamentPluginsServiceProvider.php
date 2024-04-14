<?php

namespace TomatoPHP\FilamentPlugins;

use Illuminate\Support\ServiceProvider;


class FilamentPluginsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //Register generate command
        $this->commands([
           \TomatoPHP\FilamentPlugins\Console\FilamentPluginsInstall::class,
        ]);

        //Register Config file
        $this->mergeConfigFrom(__DIR__.'/../config/filament-plugins.php', 'filament-plugins');

        //Publish Config
        $this->publishes([
           __DIR__.'/../config/filament-plugins.php' => config_path('filament-plugins.php'),
        ], 'filament-plugins-config');

        //Register Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        //Publish Migrations
        $this->publishes([
           __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'filament-plugins-migrations');
        //Register views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-plugins');

        //Publish Views
        $this->publishes([
           __DIR__.'/../resources/views' => resource_path('views/vendor/filament-plugins'),
        ], 'filament-plugins-views');

        //Register Langs
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-plugins');

        //Publish Lang
        $this->publishes([
           __DIR__.'/../resources/lang' => base_path('lang/vendor/filament-plugins'),
        ], 'filament-plugins-lang');

        //Register Routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

    }

    public function boot(): void
    {
        //you boot methods here
    }
}
