<?php

namespace TomatoPHP\FilamentPlugins;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\View\View;
use Nwidart\Modules\Facades\Module;
use TomatoPHP\FilamentPlugins\Pages\Plugins;
use TomatoPHP\FilamentPlugins\Resources\TableResource;

class FilamentPluginsPlugin implements Plugin
{

    private array $modules = [];
    private bool $autoDiscoverModules = true;

    public function getId(): string
    {
        return 'filament-plugins';
    }

    public function register(Panel $panel): void
    {
        $plugins = \TomatoPHP\FilamentPlugins\Models\Plugin::all();
        $useClusters = config('filament-plugins.clusters.enabled', false);
        if(!count($this->modules) && $this->autoDiscoverModules){
            $this->modules = Module::all();
        }
        foreach ($plugins as $plugin){
            if($plugin->type === 'plugin' && in_array($plugin->module_name, $this->modules)){
                $module = Module::find($plugin->module_name);
                if($module->isEnabled()){
                    $panel->discoverPages(
                        in: $module->appPath('Filament' . DIRECTORY_SEPARATOR . 'Pages'),
                        for: $module->appNamespace('\\Filament\\Pages')
                    );
                    $panel->discoverResources(
                        in: $module->appPath('Filament' . DIRECTORY_SEPARATOR . 'Resources'),
                        for: $module->appNamespace('\\Filament\\Resources')
                    );
                    $panel->discoverWidgets(
                        in: $module->appPath('Filament' . DIRECTORY_SEPARATOR . 'Widgets'),
                        for: $module->appNamespace('\\Filament\\Widgets')
                    );

                    $panel->discoverLivewireComponents(
                        in: $module->appPath('Livewire'),
                        for: $module->appNamespace('\\Livewire')
                    );

                    if ($useClusters) {
                        $path = $module->appPath('Filament' . DIRECTORY_SEPARATOR . 'Clusters');
                        $namespace = $module->appNamespace('\\Filament\\Clusters');
                        $panel->discoverClusters(
                            in: $path,
                            for: $namespace,
                        );
                    }
                }
            }
        }

        $panel
            ->resources([
                TableResource::class
            ])
            ->pages([
                Plugins::class
            ]);
    }

    public function autoDiscoverModules(bool $autoDiscoverModules = true)
    {
        $this->autoDiscoverModules = $autoDiscoverModules;
        return $this;
    }

    public function modules(array $modules)
    {
        $this->modules = $modules;
        return $this;
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return new static();
    }
}
