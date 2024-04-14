<?php

namespace TomatoPHP\TomatoPlugins\Services\Concerns;

use Illuminate\Support\Str;
use TomatoPHP\TomatoForms\Models\Form;

trait GenerateJsonResource
{
    private function generateJsonResource(): void
    {
        $folders = [];
        $resourceName = Str::of($this->tableName)->replace('_', ' ')->camel()->ucfirst()->toString() . 'Resource';
        if($this->moduleName){
            $folders[] = module_path($this->moduleName) . "/App/resources";
        }
        else {
            $folders[] = app_path("Http/Resources");
        }

        $this->generateStubs(
            $this->stubPath . "json.stub",
            $this->moduleName ? module_path($this->moduleName) . "/App/resources/" . $resourceName . '.php' : app_path("Http/Resources/" . $resourceName . '.php'),
            [
                "namespace" => $this->moduleName ? "Modules\\".$this->moduleName."\\App\\resources" : "App\\Http\\Resources",
                "name" => $resourceName,
                "fields" => $this->generateFields(),
                "table" => str_replace('_', '-', $this->tableName),
            ],
            $folders
        );

    }

    private function generateFields(): string
    {
        $rules = "";
        foreach ($this->table->tableCols as $key => $item) {
            if ($item->name !== 'id') {
                if($key !== 0){
                    $rules .= "            ";
                }

                if($item->foreign){
                    $rules .= "'".Str::of($item->name)->remove('_id')->toString()."' => \$this->". $item->foreign_col . ',';
                }
                else {
                    $rules .= "'{$item->name}' => \$this->{$item->name},";
                }
            }

            $rules .= "\n";
        }

        return $rules;
    }
}
