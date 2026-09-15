<?php

namespace TomatoPHP\FilamentPlugins;

use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;
use Throwable;
use TomatoPHP\FilamentPlugins\Models\Plugin as PluginModel;
use TomatoPHP\FilamentPlugins\Pages\Plugins;
use TomatoPHP\FilamentPlugins\Resources\TableResource;

class FilamentPluginsPlugin implements Plugin
{
    /**
     * Feature name => config key used when the option is not set on the plugin.
     */
    public const FEATURES = [
        'create' => 'allow_create',
        'import' => 'allow_upload',
        'toggle' => 'allow_toggle',
        'destroy' => 'allow_destroy',
        'generator' => 'allow_generator',
    ];

    private array $modules = [];

    private bool $useUI = true;

    private bool $autoDiscoverModules = true;

    private bool $discoverCurrentPanelOnly = false;

    /**
     * @var array<string, bool>
     */
    private array $allowed = [];

    public function getId(): string
    {
        return 'filament-plugins';
    }

    public function register(Panel $panel): void
    {
        $plugins = PluginModel::all();
        $useClusters = config('filament-plugins.clusters.enabled', false);
        $modules = $this->modules;
        if (! count($modules) && $this->autoDiscoverModules) {
            $modules = array_keys(Module::all());
        }
        foreach ($plugins as $plugin) {
            if ($plugin->type === 'plugin' && in_array($plugin->module_name, $modules)) {
                $module = Module::find($plugin->module_name);
                if (! $module) {
                    continue;
                }
                $dir = File::directories($module->getPath());
                if ($module->isEnabled() && ! in_array($module->getPath().DIRECTORY_SEPARATOR.'src', $dir)) {
                    $checkIfThereIsDirectoryForThisPanel = File::exists($module->appPath('Filament'.DIRECTORY_SEPARATOR.Str::studly($panel->getId())));
                    if ($checkIfThereIsDirectoryForThisPanel && $this->discoverCurrentPanelOnly) {
                        $panel->discoverPages(
                            in: $module->appPath('Filament'.DIRECTORY_SEPARATOR.Str::studly($panel->getId()).DIRECTORY_SEPARATOR.'Pages'),
                            for: $module->appNamespace('\\Filament\\'.Str::studly($panel->getId()).'\\Pages')
                        );
                        $panel->discoverResources(
                            in: $module->appPath('Filament'.DIRECTORY_SEPARATOR.Str::studly($panel->getId()).DIRECTORY_SEPARATOR.'Resources'),
                            for: $module->appNamespace('\\Filament\\'.Str::studly($panel->getId()).'\\Resources')
                        );
                        $panel->discoverWidgets(
                            in: $module->appPath('Filament'.DIRECTORY_SEPARATOR.Str::studly($panel->getId()).DIRECTORY_SEPARATOR.'Widgets'),
                            for: $module->appNamespace('\\Filament\\'.Str::studly($panel->getId()).'\\Widgets')
                        );

                        $panel->discoverLivewireComponents(
                            in: $module->appPath('Livewire'),
                            for: $module->appNamespace('\\Livewire')
                        );

                        if ($useClusters) {
                            $panel->discoverClusters(
                                in: $module->appPath('Filament'.DIRECTORY_SEPARATOR.Str::studly($panel->getId()).DIRECTORY_SEPARATOR.'Clusters'),
                                for: $module->appNamespace('\\Filament\\'.Str::studly($panel->getId()).'\\Clusters'),
                            );
                        }
                    } else {
                        $panel->discoverPages(
                            in: $module->appPath('Filament'.DIRECTORY_SEPARATOR.'Pages'),
                            for: $module->appNamespace('\\Filament\\Pages')
                        );
                        $panel->discoverResources(
                            in: $module->appPath('Filament'.DIRECTORY_SEPARATOR.'Resources'),
                            for: $module->appNamespace('\\Filament\\Resources')
                        );
                        $panel->discoverWidgets(
                            in: $module->appPath('Filament'.DIRECTORY_SEPARATOR.'Widgets'),
                            for: $module->appNamespace('\\Filament\\Widgets')
                        );

                        $panel->discoverLivewireComponents(
                            in: $module->appPath('Livewire'),
                            for: $module->appNamespace('\\Livewire')
                        );

                        if ($useClusters) {
                            $panel->discoverClusters(
                                in: $module->appPath('Filament'.DIRECTORY_SEPARATOR.'Clusters'),
                                for: $module->appNamespace('\\Filament\\Clusters'),
                            );
                        }
                    }
                }
            }
        }

        if ($this->useUI) {
            $panel->pages([
                Plugins::class,
            ]);

            if ($this->isAllowed('generator')) {
                $panel->resources([
                    TableResource::class,
                ]);
            }
        }

        foreach ($panel->getPlugins() as $modulePlugin) {
            $module = Module::find(str(get_class($modulePlugin))->explode('\\')[1] ?? '');
            if ($module && ! $module->isEnabled()) {
                $panel->disablePlugin($modulePlugin);
            }
        }
    }

    public function autoDiscoverModules(bool $autoDiscoverModules = true): static
    {
        $this->autoDiscoverModules = $autoDiscoverModules;

        return $this;
    }

    public function modules(array $modules): static
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
        return app(static::class);
    }

    /**
     * The plugin registered on the current panel, if any.
     */
    public static function get(): ?static
    {
        try {
            $panel = Filament::getCurrentOrDefaultPanel();

            if ($panel?->hasPlugin('filament-plugins')) {
                /** @var static */
                return $panel->getPlugin('filament-plugins');
            }
        } catch (Throwable) {
            //
        }

        return null;
    }

    /**
     * Is a file-writing feature (create, import, toggle, destroy, generator) allowed on the current panel?
     */
    public static function allows(string $feature): bool
    {
        return static::get()?->isAllowed($feature) ?? (bool) config('filament-plugins.'.static::FEATURES[$feature], true);
    }

    public function isAllowed(string $feature): bool
    {
        return $this->allowed[$feature] ?? (bool) config('filament-plugins.'.static::FEATURES[$feature], true);
    }

    /**
     * "Create Plugin" action: runs `module:make` and writes the plugin files.
     */
    public function allowCreate(bool $condition = true): static
    {
        $this->allowed['create'] = $condition;

        return $this;
    }

    /**
     * "Import Plugin" action: extracts an uploaded ZIP file into the modules folder.
     */
    public function allowImport(bool $condition = true): static
    {
        $this->allowed['import'] = $condition;

        return $this;
    }

    /**
     * Enable / disable actions for one module or all modules.
     */
    public function allowToggle(bool $condition = true): static
    {
        $this->allowed['toggle'] = $condition;

        return $this;
    }

    /**
     * Delete action: removes the module folder.
     */
    public function allowDestroy(bool $condition = true): static
    {
        $this->allowed['destroy'] = $condition;

        return $this;
    }

    /**
     * Tables builder, migrations and the model / resource / page / widget generator.
     */
    public function allowGenerator(bool $condition = true): static
    {
        $this->allowed['generator'] = $condition;

        return $this;
    }

    public function useUI(bool $useUI): static
    {
        $this->useUI = $useUI;

        return $this;
    }

    public function discoverCurrentPanelOnly(bool $discoverCurrentPanelOnly = true): static
    {
        $this->discoverCurrentPanelOnly = $discoverCurrentPanelOnly;

        return $this;
    }
}
