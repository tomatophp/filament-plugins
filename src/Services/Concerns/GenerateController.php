<?php

namespace TomatoPHP\TomatoPlugins\Services\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait GenerateController
{
    private function generateController(bool $isForce = false)
    {
        $filePath = $this->moduleName ? module_path($this->moduleName) ."/App/Http/Controllers/{$this->modelName}Controller.php" : app_path("Http/Controllers/Admin/{$this->modelName}Controller.php");
        if($isForce){
            if(File::exists($filePath)){
                File::delete($filePath);
            }
        }
        $this->generateStubs(
            $this->stubPath . "controller.stub",
            $this->moduleName ? module_path($this->moduleName) ."/App/Http/Controllers/{$this->modelName}Controller.php" : app_path("Http/Controllers/Admin/{$this->modelName}Controller.php"),
            [
                "name" => "{$this->modelName}Controller",
                "model" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\App\\Models\\".$this->modelName : "\\App\\Models\\".$this->modelName,
                "title" => $this->modelName,
                "table" => str_replace('_', '-', $this->tableName),
                "validation" => $this->generateRules(),
                "validationEdit" => $this->generateRules(true),
                "requestNamespace" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\App\\Http\\Requests\\{$this->modelName}\\" : "\\App\\Http\\Requests\\Admin\\{$this->modelName}\\",
                "tableClass" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\App\\Tables\\".$this->modelName."Table" : "\\App\\Tables\\".$this->modelName."Table",
                "namespace" => $this->moduleName ? "Modules\\".$this->moduleName."\\App\\Http\\Controllers": "App\\Http\\Controllers\\Admin",
                "modulePath" => $this->moduleName ? Str::replace('_', '-', Str::lower($this->moduleName))."::" : "admin."
            ],
            [
                $this->moduleName ? module_path($this->moduleName) ."/App/Http/Controllers/" : app_path("Http/Controllers/Admin")
            ]
        );
    }

    private function generateControllerForRequest()
    {
        $this->generateStubs(
            $this->stubPath . "controller-request.stub",
            $this->moduleName ? module_path($this->moduleName) ."/App/Http/Controllers/{$this->modelName}Controller.php" : app_path("Http/Controllers/Admin/{$this->modelName}Controller.php"),
            [
                "name" => "{$this->modelName}Controller",
                "model" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\App\\Models\\".$this->modelName : "\\App\\Models\\".$this->modelName,
                "title" => $this->modelName,
                "table" => str_replace('_', '-', $this->tableName),
                "requestNamespace" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\App\\Http\\Requests\\{$this->modelName}\\" : "\\App\\Http\\Requests\\Admin\\{$this->modelName}\\",
                "tableClass" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\App\\Tables\\".$this->modelName."Table" : "\\App\\Tables\\".$this->modelName."Table",
                "namespace" => $this->moduleName ? "Modules\\".$this->moduleName."\\App\\Http\\Controllers": "App\\Http\\Controllers\\Admin",
                "modulePath" => $this->moduleName ? Str::replace('_', '-', Str::lower($this->moduleName))."::" : "admin."
            ],
            [
                $this->moduleName ? module_path($this->moduleName) ."/App/Http/Controllers/" : app_path("Http/Controllers/Admin")
            ]
        );
    }

    private function generateControllerForBuilder()
    {
        $this->generateStubs(
            $this->stubPath ."FormBuilder/BuilderController.stub",
            $this->moduleName ? module_path($this->moduleName) ."/App/Http/Controllers/{$this->modelName}Controller.php" : app_path("Http/Controllers/Admin/{$this->modelName}Controller.php"),
            [
                "name" => "{$this->modelName}Controller",
                "model" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\App\\Models\\".$this->modelName : "\\App\\Models\\".$this->modelName,
                "title" => $this->modelName,
                "table" => str_replace('_', '-', $this->tableName),
                "validation" => $this->generateRules(),
                "validationEdit" => $this->generateRules(true),
                "formClass" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\App\\Forms\\{$this->modelName}Form" : "\\App\\Forms\\{$this->modelName}Form",
                "requestNamespace" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\App\\Http\\Requests\\{$this->modelName}\\" : "\\App\\Http\\Requests\\Admin\\{$this->modelName}\\",
                "FormNamespace" => $this->moduleName ? "Modules\\".$this->moduleName."\\App\\Forms\\{$this->modelName}Form" : "App\\Forms\\{$this->modelName}Form",
                "tableClass" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\App\\Tables\\".$this->modelName."Table" : "\\App\\Tables\\".$this->modelName."Table",
                "namespace" => $this->moduleName ? "Modules\\".$this->moduleName."\\App\\Http\\Controllers": "App\\Http\\Controllers\\Admin",
                "modulePath" => $this->moduleName ? Str::replace('_', '-', Str::lower($this->moduleName))."::" : "admin."
            ],
            [
                $this->moduleName ? module_path($this->moduleName) ."/App/Http/Controllers/" : app_path("Http/Controllers/Admin")
            ]
        );
    }
}
