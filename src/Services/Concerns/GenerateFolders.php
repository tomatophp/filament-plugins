<?php

namespace TomatoPHP\TomatoPlugins\Services\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait GenerateFolders
{
    private function generateFolders(): void
    {
        if($this->moduleName){
            $folders = [
                module_path($this->moduleName) ."/App/Http/Controllers/",
                module_path($this->moduleName) ."/App/resources/",
                module_path($this->moduleName) ."/App/Http/Requests/",
                module_path($this->moduleName) ."/App/Http/Requests/{$this->modelName}",
                module_path($this->moduleName) ."/App/Models/",
                module_path($this->moduleName) . "/resources/views/" . str_replace('_', '-', $this->tableName),
                module_path($this->moduleName) . "/routes",
                module_path($this->moduleName)."/App/Tables",
                module_path($this->moduleName)."/App/Forms",
            ];
        }
        else {
            $folders = [
                app_path("Http/Controllers") . "/Admin",
                app_path("Http/Resources"),
                app_path("Http/Requests"),
                app_path("Http/Requests/Admin"),
                app_path("Http/Requests/Admin/{$this->modelName}"),
                app_path("Http/Resources") . "/Admin",
                resource_path("views") . '/admin',
                resource_path("views") . '/admin/' . str_replace('_', '-', $this->tableName),
                base_path("routes"),
                app_path("Tables")
            ];
        }

        foreach($folders as $folder){
            if(!File::exists($folder)){
                File::makeDirectory($folder);
                File::put($folder . "/.gitkeep", "");
            }
        }
    }
}
