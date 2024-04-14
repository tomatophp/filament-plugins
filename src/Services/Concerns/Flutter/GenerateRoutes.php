<?php

namespace TomatoPHP\TomatoPlugins\Services\Concerns\Flutter;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait GenerateRoutes
{
    private bool $hasJson = false;

    public function generateRoutes(): void
    {
        $routeListPath = base_path('/flutter/' . $this->appName . '/lib/app/modules/'.$this->module.'/routes/'.$this->module.'Router.dart');
        $routeNamesPath = base_path('/flutter/' . $this->appName . '/lib/app/modules/'.$this->module.'/routes/'.$this->module.'Routes.dart');

        //Delete Single If Exists
        if(File::exists($routeListPath)){
            File::delete($routeListPath);
        }

        //Delete Table If Exists
        if(File::exists($routeNamesPath)){
            File::delete($routeNamesPath);
        }

        //Create Single Model
        $this->generateStubs(
            $this->stubPath . "/routes/list.stub",
            $routeListPath,
            [
                "module" => $this->module,
                "moduleLower" => $this->moduleLower,
                "table" => $this->table,
                "route" => Str::of($this->table)->replace('_', '-')->lower(),
            ]
        );

        //Create Single Model
        $this->generateStubs(
            $this->stubPath . "/routes/name.stub",
            $routeNamesPath,
            [
                "module" => $this->module,
                "moduleLower" => $this->moduleLower,
                "table" => Str::of($this->table)->replace('_', '-')->lower(),
            ]
        );

    }

}
