<?php

namespace TomatoPHP\FilamentPlugins\Services\Traits;

use TomatoPHP\FilamentPlugins\Services\ModulePaths;

trait GenerateReadMe
{
    private function generateReadMe(): void
    {
        $this->generateStubs(
            $this->stubPath.'readme.stub',
            ModulePaths::modulesPath($this->name.'/README.md'),
            [
                'name' => $this->name,
                'title' => $this->title,
                'description' => $this->description,
            ],
            [
                ModulePaths::modulesPath(),
                ModulePaths::modulesPath($this->name),
            ]
        );
    }
}
