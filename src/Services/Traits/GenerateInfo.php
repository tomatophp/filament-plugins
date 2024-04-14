<?php

namespace TomatoPHP\TomatoPlugins\Services\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;

trait GenerateInfo
{
    /**
     * @return void
     */
    private function generateInfo(): void
    {
        if(Module::find($this->name)){
            $modulePath = module_path($this->name) .'/module.json';
            $module = json_decode(File::get($modulePath));
            $module->title = [];
            $module->title['ar'] = $this->title;
            $module->title['en'] = $this->title;
            $module->title['gr'] = $this->title;
            $module->title['sp'] = $this->title;
            $module->description = [];
            $module->description['ar'] = $this->description;
            $module->description['en'] = $this->description;
            $module->description['gr'] = $this->description;
            $module->description['sp'] = $this->description;
            $module->color = $this->color;
            $module->icon = $this->icon;
            $module->placeholder = "placeholder.webp";
            $module->type = "plugin";
            $module->version = "v1.0";

            File::put($modulePath, json_encode($module, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        }
    }
}
