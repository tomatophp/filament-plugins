<?php

namespace TomatoPHP\TomatoPlugins\Services\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;

trait MoveFiles
{
    /**
     * @return void
     */
    private function moveFiles(): void
    {
        if(Module::find($this->name)){
            $modulePath = module_path($this->name);

            File::copy($this->stubPath .'/publish/CHANGELOG.md', $modulePath.'/CHANGELOG.md');
            File::copy($this->stubPath .'/publish/LICENSE.md', $modulePath.'/LICENSE.md');
            File::copy($this->stubPath .'/publish/SECURITY.md', $modulePath.'/SECURITY.md');
        }
    }
}
