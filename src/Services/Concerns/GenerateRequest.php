<?php

namespace TomatoPHP\TomatoPlugins\Services\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use TomatoPHP\TomatoForms\Models\Form;

trait GenerateRequest
{
    private function generateRequest(): void
    {
        $this->generateStubs(
            $this->stubPath . "request.stub",
            $this->moduleName ? module_path($this->moduleName) ."/App/Http/Requests/{$this->modelName}/{$this->modelName}StoreRequest.php" : app_path("Http/Requests/Admin/{$this->modelName}/{$this->modelName}StoreRequest.php"),
            [
                "name" => "{$this->modelName}StoreRequest",
                "model" => $this->modelName,
                "validation" => $this->generateRules(),
                "namespace" => $this->moduleName ? "Modules\\{$this->moduleName}\\App\\Http\\Requests\\{$this->modelName}" : "App\\Http\\Requests\\Admin\\{$this->modelName}",
            ],
            [
                $this->moduleName ? module_path($this->moduleName) ."/App/Http/Requests": app_path("Http/Requests/Admin"),
                $this->moduleName ? module_path($this->moduleName) ."/App/Http/Requests/{$this->modelName}": app_path("Http/Requests/Admin/{$this->modelName}"),
            ]
        );

        $this->generateStubs(
            $this->stubPath . "request.stub",
            $this->moduleName ? module_path($this->moduleName)."/App/Http/Requests/{$this->modelName}/{$this->modelName}UpdateRequest.php" : app_path("Http/Requests/Admin/{$this->modelName}/{$this->modelName}UpdateRequest.php"),
            [
                "name" => "{$this->modelName}UpdateRequest",
                "model" => $this->modelName,
                "validation" => $this->generateRules(true),
                "namespace" => $this->moduleName ? "Modules\\{$this->moduleName}\\App\\Http\\Requests\\{$this->modelName}" : "App\\Http\\Requests\\Admin\\{$this->modelName}",
            ],
            [
                $this->moduleName ? module_path($this->moduleName) ."/App/Http/Requests": app_path("Http/Requests/Admin"),
                $this->moduleName ? module_path($this->moduleName) ."/App/Http/Requests/{$this->modelName}": app_path("Http/Requests/Admin/{$this->modelName}"),
            ]
        );
    }
}
