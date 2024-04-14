<?php

namespace TomatoPHP\TomatoPlugins\Services\Concerns\Flutter;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait GenerateModule
{
    private bool $hasJson = false;

    public function generateModule(): void
    {
        $modulePath = base_path('/flutter/' . $this->appName . '/lib/app/modules/'.$this->module.'/'.$this->module.'Module.dart');

        //Delete Single If Exists
        if(File::exists($modulePath)){
            File::delete($modulePath);
        }

        //Create Single Model
        $this->generateStubs(
            $this->stubPath . "/module.stub",
            $modulePath,
            [
                "module" => $this->module
            ]
        );
    }

}
