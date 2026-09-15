<?php

namespace TomatoPHP\FilamentPlugins\Console;

use Filament\Support\Commands\Exceptions\FailureCommandOutput;
use Filament\Widgets\Commands\MakeWidgetCommand;
use Filament\Widgets\Widget;
use TomatoPHP\FilamentPlugins\Console\Concerns\InteractsWithModule;

/**
 * Filament v5 `make:filament-widget`, writing into a module.
 */
class FilamentWidgetGenerate extends MakeWidgetCommand
{
    use InteractsWithModule;

    protected $name = 'filament-plugins:widget';

    protected $description = 'Create a new Filament widget class in a module';

    /**
     * @var array<string>
     */
    protected $aliases = [];

    protected function getArguments(): array
    {
        return [
            ...parent::getArguments(),
            $this->getModuleArgument(),
        ];
    }

    public function handle(): int
    {
        try {
            $this->configurePluginModule();
        } catch (FailureCommandOutput) {
            return static::FAILURE;
        }

        return parent::handle();
    }

    protected function configureResourcesLocation(string $question): void
    {
        if (filled($this->clusterFqn)) {
            return;
        }

        $this->resourcesNamespace = $this->moduleNamespace('Filament\\Resources');
        $this->resourcesDirectory = $this->moduleDirectory('Filament/Resources');
    }

    protected function configureWidgetsLocation(): void
    {
        if (filled($this->resourceFqn)) {
            return;
        }

        if (! $this->panel) {
            $this->widgetsNamespace = $this->moduleNamespace('Livewire');
            $this->widgetsDirectory = $this->moduleDirectory('Livewire');

            return;
        }

        $this->widgetsNamespace = $this->moduleNamespace('Filament\\Widgets');
        $this->widgetsDirectory = $this->moduleDirectory('Filament/Widgets');
    }

    protected function configureLocation(): void
    {
        $this->fqn = $this->widgetsNamespace.'\\'.$this->fqnEnd;

        if ($this->type === Widget::class) {
            [$this->view, $this->viewPath] = $this->moduleViewLocation($this->fqn);
        }
    }
}
