<?php

namespace TomatoPHP\FilamentPlugins\Services\Traits;

use Nwidart\Modules\Facades\Module;
use TomatoPHP\FilamentPlugins\Services\ModulePaths;

trait GeneratePage
{
    public function generatePage(): void
    {
        $module = Module::find($this->name);

        if (! $module) {
            return;
        }

        $this->generateStubs(
            $this->stubPath.'page.stub',
            ModulePaths::appPath($module, 'Filament/Pages/'.$this->name.'Page.php'),
            [
                'namespace' => ModulePaths::appNamespace($module, 'Filament\\Pages'),
                'view' => $module->getLowerName().'::index',
                'title' => $this->title,
                'icon' => $this->icon,
                'name' => $this->name.'Page',
            ],
            [
                ModulePaths::appPath($module),
                ModulePaths::appPath($module, 'Filament'),
                ModulePaths::appPath($module, 'Filament/Pages'),
            ]
        );
    }
}
