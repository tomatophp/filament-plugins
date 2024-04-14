<?php

namespace TomatoPHP\TomatoPlugins\Services\Concerns;

trait GenerateRoutes
{
    private function generateRoutes()
    {
        if($this->routes){
            $this->generateStubs(
                $this->stubPath . "route.stub",
                $this->moduleName ? module_path($this->moduleName) . "/routes/web.php" : base_path("routes/web.php"),
                [
                    "name" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\App\\Http\\Controllers\\{$this->modelName}Controller" : "App\\Http\\Controllers\\Admin\\{$this->modelName}Controller",
                    "table" => str_replace('_', '-', $this->tableName)
                ],
                [
                    $this->moduleName ? module_path($this->moduleName) . "/Routes" : base_path("routes")
                ],
                true
            );
        }

        if($this->apiRoutes){
            $this->generateStubs(
                $this->stubPath . "api-route.stub",
                $this->moduleName ? module_path($this->moduleName) . "/routes/api.php" : base_path("routes/api.php"),
                [
                    "name" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\App\\Http\\Controllers\\{$this->modelName}Controller" : "App\\Http\\Controllers\\Admin\\{$this->modelName}Controller",
                    "table" => str_replace('_', '-', $this->tableName)
                ],
                [
                    $this->moduleName ? module_path($this->moduleName) . "/Routes" : base_path("routes")
                ],
                true
            );
        }
    }
}
