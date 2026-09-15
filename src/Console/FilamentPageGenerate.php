<?php

namespace TomatoPHP\FilamentPlugins\Console;

use Filament\Commands\MakePageCommand;
use Filament\Resources\Pages\Page as ResourcePage;
use Filament\Support\Commands\Exceptions\FailureCommandOutput;
use TomatoPHP\FilamentPlugins\Console\Concerns\InteractsWithModule;

/**
 * Filament v5 `make:filament-page`, writing into a module.
 */
class FilamentPageGenerate extends MakePageCommand
{
    use InteractsWithModule;

    protected $name = 'filament-plugins:page';

    protected $description = 'Create a new Filament page class in a module';

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

    protected function configurePagesLocation(): void
    {
        if (filled($this->resourceFqn) || filled($this->clusterFqn)) {
            return;
        }

        $this->pagesNamespace = $this->moduleNamespace('Filament\\Pages');
        $this->pagesDirectory = $this->moduleDirectory('Filament/Pages');
    }

    protected function configureLocation(): void
    {
        $this->fqn = $this->pagesNamespace.'\\'.$this->fqnEnd;

        if ((! $this->hasResource) || ($this->resourcePageType === ResourcePage::class)) {
            [$this->view, $this->viewPath] = $this->moduleViewLocation($this->fqn);
        }
    }
}
