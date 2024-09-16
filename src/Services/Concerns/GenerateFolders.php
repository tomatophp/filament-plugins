<?php

namespace TomatoPHP\FilamentPlugins\Services\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;

trait GenerateFolders
{
    private function generateFolders(): void
    {
        $module = Module::find($this->moduleName);
        $appPath = 'app';
        $moduleDir = File::directories($module->getPath());
        if(in_array($module->getPath() .'/src', $moduleDir)){
            $appPath = 'src';
        }

        if($this->moduleName){
            $folders = [
//                module_path($this->moduleName) .'/'.$appPath . "/Http/Controllers/",
//                module_path($this->moduleName) .'/'.$appPath . "/resources/",
//                module_path($this->moduleName) .'/'.$appPath . "/Http/Requests/",
//                module_path($this->moduleName) .'/'.$appPath . "/Http/Requests/{$this->modelName}",
                module_path($this->moduleName) .'/'.$appPath . "/Models/",
//                module_path($this->moduleName) . "/resources/views/" . str_replace('_', '-', $this->tableName),
//                module_path($this->moduleName) . "/routes",
//                module_path($this->moduleName).'/'.$appPath . "/Tables",
//                module_path($this->moduleName).'/'.$appPath . "/Forms",
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
