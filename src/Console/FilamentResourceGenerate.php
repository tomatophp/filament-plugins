<?php

namespace TomatoPHP\FilamentPlugins\Console;

use Filament\Commands\MakeResourceCommand;
use Filament\Support\Commands\Exceptions\FailureCommandOutput;
use TomatoPHP\FilamentPlugins\Console\Concerns\InteractsWithModule;

/**
 * Filament v5 `make:filament-resource`, writing into a module.
 */
class FilamentResourceGenerate extends MakeResourceCommand
{
    use InteractsWithModule;

    protected $name = 'filament-plugins:resource';

    protected $description = 'Create a new Filament resource in a module';

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

        if (blank($this->option('model-namespace'))) {
            $this->input->setOption('model-namespace', $this->moduleNamespace('Models'));
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
}
