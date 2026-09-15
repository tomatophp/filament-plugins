<?php

namespace TomatoPHP\FilamentPlugins\Console\Concerns;

use Filament\Support\Commands\Exceptions\FailureCommandOutput;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module as Modules;
use Nwidart\Modules\Module;
use ReflectionClass;
use Symfony\Component\Console\Input\InputArgument;
use TomatoPHP\FilamentPlugins\Services\ModulePaths;

use function Laravel\Prompts\text;

trait InteractsWithModule
{
    protected ?Module $pluginModule = null;

    /**
     * Use the stubs shipped with the Filament command this class extends.
     */
    protected function getDefaultStubPath(): string
    {
        $reflectionClass = new ReflectionClass(get_parent_class($this));

        return (string) str($reflectionClass->getFileName())
            ->beforeLast('Commands')
            ->append('../stubs');
    }

    protected function getModuleArgument(): InputArgument
    {
        return new InputArgument(
            name: 'module',
            mode: InputArgument::OPTIONAL,
            description: 'The module to create the class in',
        );
    }

    protected function configurePluginModule(): void
    {
        $name = $this->argument('module') ?? text(
            label: 'In which module should we create this?',
            placeholder: 'Blog',
            required: true,
        );

        $module = Modules::find((string) $name);

        if (! $module) {
            $this->components->error("Module [{$name}] not found.");

            throw new FailureCommandOutput;
        }

        $this->pluginModule = $module;
    }

    protected function moduleNamespace(string $relativeNamespace = ''): string
    {
        return ModulePaths::appNamespace($this->pluginModule, $relativeNamespace);
    }

    protected function moduleDirectory(string $relativePath = ''): string
    {
        return ModulePaths::appPath($this->pluginModule, $relativePath);
    }

    /**
     * Put the Blade view inside the module (`{module}::filament.pages.my-page`).
     *
     * @return array{0: string, 1: string}
     */
    protected function moduleViewLocation(string $fqn): array
    {
        $segments = str($fqn)
            ->after($this->moduleNamespace().'\\')
            ->replace('\\', '/')
            ->explode('/')
            ->map(fn (string $segment): string => Str::kebab($segment));

        $view = $this->pluginModule->getLowerName().'::'.$segments->implode('.');
        $viewPath = ModulePaths::viewsPath($this->pluginModule, $segments->implode('/').'.blade.php');

        return [$view, $viewPath];
    }
}
