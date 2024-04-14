<?php

namespace TomatoPHP\TomatoPlugins\Services\Concerns;

trait GenerateMenus
{
    private function generateMenus()
    {
        $this->injectString(
            $this->moduleName ? module_path($this->moduleName) ."/App/Providers/{$this->moduleName}ServiceProvider.php" : app_path("Providers/AppServiceProvider.php"),
            $this->moduleName ? '$this->loadMigrationsFrom(module_path($this->moduleName, \'Database/migrations\'));' :  'public function boot(): void',
            $this->stubPath . "menu.stub",
            [
                "moduleName" =>$this->moduleName ? $this->moduleName : 'App',
                "title" => $this->modelName,
                "tableName" => str_replace('_', '-', $this->tableName)
            ],
            $this->moduleName ? 1 : 2
        );
    }
}
