<?php

namespace TomatoPHP\FilamentPlugins;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Laravel\Module;


class FilamentPluginsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //Register generate command
        $this->commands([
           \TomatoPHP\FilamentPlugins\Console\FilamentPluginsInstall::class,
           \TomatoPHP\FilamentPlugins\Console\FilamentPageGenerate::class,
           \TomatoPHP\FilamentPlugins\Console\FilamentResourceGenerate::class,
           \TomatoPHP\FilamentPlugins\Console\FilamentWidgetGenerate::class,
           \TomatoPHP\FilamentPlugins\Console\FilamentPluginsGenerate::class,
           \TomatoPHP\FilamentPlugins\Console\FilamentPluginsModel::class,
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

        $this->registerModuleMacros();
    }

    public function boot(): void
    {

    }


    protected function registerModuleMacros(): void
    {
        Module::macro('namespace', function (string $relativeNamespace = '') {
            $base = trim($this->app['config']->get('modules.namespace', 'Modules'), '\\');
            $relativeNamespace = trim($relativeNamespace, '\\');
            $studlyName = $this->getStudlyName();

            return trim("{$base}\\{$studlyName}\\{$relativeNamespace}", '\\');
        });

        Module::macro('getTitle', function () {
            return str($this->getStudlyName())->kebab()->title()->replace('-', ' ')->toString();
        });

        Module::macro('appNamespace', function (string $relativeNamespace = '') {
            $relativeNamespace = trim($relativeNamespace, '\\');
            $relativeNamespace = trim($relativeNamespace, '\\');
            $relativeNamespace = $relativeNamespace;

            return $this->namespace($relativeNamespace);
        });
        Module::macro('appPath', function (string $relativePath = '') {
            $appPath = $this->getExtraPath('app');

            return $appPath . ($relativePath ? DIRECTORY_SEPARATOR . $relativePath : '');
        });

        Module::macro('databasePath', function (string $relativePath = '') {
            $appPath = $this->getExtraPath('database');

            return $appPath . ($relativePath ? DIRECTORY_SEPARATOR . $relativePath : '');
        });

        Module::macro('resourcesPath', function (string $relativePath = '') {
            $appPath = $this->getExtraPath('resources');

            return $appPath . ($relativePath ? DIRECTORY_SEPARATOR . $relativePath : '');
        });

        Module::macro('migrationsPath', function (string $relativePath = '') {
            $appPath = $this->databasePath('migrations');

            return $appPath . ($relativePath ? DIRECTORY_SEPARATOR . $relativePath : '');
        });

        Module::macro('seedersPath', function (string $relativePath = '') {
            $appPath = $this->databasePath('seeders');

            return $appPath . ($relativePath ? DIRECTORY_SEPARATOR . $relativePath : '');
        });

        Module::macro('factoriesPath', function (string $relativePath = '') {
            $appPath = $this->databasePath('factories');

            return $appPath . ($relativePath ? DIRECTORY_SEPARATOR . $relativePath : '');
        });
    }

}
