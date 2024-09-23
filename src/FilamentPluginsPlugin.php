<?php

namespace TomatoPHP\FilamentPlugins;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Nwidart\Modules\Facades\Module;
use TomatoPHP\FilamentPlugins\Pages\Plugins;
use TomatoPHP\FilamentPlugins\Resources\TableResource;

class FilamentPluginsPlugin implements Plugin
{

    private array $modules = [];
    private bool $useUI = true;
    private bool $autoDiscoverModules = true;
    private bool $discoverCurrentPanelOnly = false;

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
                $dir = File::directories($module->getPath());
                if($module->isEnabled() && !in_array($module->getPath() . DIRECTORY_SEPARATOR . 'src', $dir)){
                    $checkIfThereIsDirectoryForThisPanel = File::exists($module->appPath('Filament' . DIRECTORY_SEPARATOR . Str::studly($panel->getId())));
                    if($checkIfThereIsDirectoryForThisPanel && $this->discoverCurrentPanelOnly){
                        $panel->discoverPages(
                            in: $module->appPath('Filament' . DIRECTORY_SEPARATOR .  Str::studly($panel->getId()) . DIRECTORY_SEPARATOR . 'Pages'),
                            for: $module->appNamespace('\\Filament\\'.Str::studly($panel->getId()).'\\Pages')
                        );
                        $panel->discoverResources(
                            in: $module->appPath('Filament' . DIRECTORY_SEPARATOR .  Str::studly($panel->getId()) . DIRECTORY_SEPARATOR . 'Resources'),
                            for: $module->appNamespace('\\Filament\\'.Str::studly($panel->getId()).'\\Resources')
                        );
                        $panel->discoverWidgets(
                            in: $module->appPath('Filament' . DIRECTORY_SEPARATOR .  Str::studly($panel->getId()) . DIRECTORY_SEPARATOR . 'Widgets'),
                            for: $module->appNamespace('\\Filament\\'.Str::studly($panel->getId()).'\\Widgets')
                        );

                        $panel->discoverLivewireComponents(
                            in: $module->appPath('Livewire'),
                            for: $module->appNamespace('\\Livewire')
                        );

                        if ($useClusters) {
                            $path = $module->appPath('Filament' . DIRECTORY_SEPARATOR .  Str::studly($panel->getId()) . DIRECTORY_SEPARATOR . 'Clusters');
                            $namespace = $module->appNamespace('\\Filament\\'.Str::studly($panel->getId()).'\\Clusters');
                            $panel->discoverClusters(
                                in: $path,
                                for: $namespace,
                            );
                        }
                    }
                    else {
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
        }

        if($this->useUI){
            $panel
                ->resources([
                    TableResource::class
                ])
                ->pages([
                    Plugins::class
                ]);
        }

        foreach ($panel->getPlugins() as $key=>$modulePlugin){
            $module = Module::find(str(get_class($modulePlugin))->explode('\\')[1]);
            if($module && !$module->isEnabled()){
                $panel->disablePlugin($modulePlugin);
            }
        }

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

    public function useUI(bool $useUI): static
    {
        $this->useUI = $useUI;
        return $this;
    }

    public function discoverCurrentPanelOnly(bool $discoverCurrentPanelOnly=true): static
    {
        $this->discoverCurrentPanelOnly = $discoverCurrentPanelOnly;
        return $this;
    }
}
