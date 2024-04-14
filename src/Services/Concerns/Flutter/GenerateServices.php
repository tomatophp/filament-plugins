<?php

namespace TomatoPHP\TomatoPlugins\Services\Concerns\Flutter;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait GenerateServices
{
    private function generateServices(): void
    {
        $appServicePath = base_path('/flutter/' . $this->appName . '/lib/app/modules/'.$this->module.'/services/App'.$this->module.'Service.dart');
        $appServiceInterfacePath = base_path('/flutter/' . $this->appName . '/lib/app/modules/'.$this->module.'/services/'.$this->module.'Service.dart');
        $appServiceMockPath = base_path('/flutter/' . $this->appName . '/lib/app/modules/'.$this->module.'/services/Mock'.$this->module.'Service.dart');

        //Delete App Service If Exists
        if (File::exists($appServicePath)) {
            File::delete($appServicePath);
        }

        //Delete App Service Interface If Exists
        if (File::exists($appServiceInterfacePath)) {
            File::delete($appServiceInterfacePath);
        }

        //Delete App Service Mock If Exists
        if (File::exists($appServiceMockPath)) {
            File::delete($appServiceMockPath);
        }

        //Create App Service
        $this->generateStubs(
            $this->stubPath . "/services/app.stub",
            $appServicePath,
            [
                "module" => $this->module,
                "table" => $this->table,
                "route" => Str::of($this->table)->replace('_', '-')->lower(),
                "moduleLower" => $this->moduleLower,
            ]
        );

        //Create App Service Interface
        $this->generateStubs(
            $this->stubPath . "/services/interface.stub",
            $appServiceInterfacePath,
            [
                "module" => $this->module,
                "table" => $this->table,
                "moduleLower" => $this->moduleLower,
            ]
        );

        //Create App Service Mock
        $this->generateStubs(
            $this->stubPath . "/services/mock.stub",
            $appServiceMockPath,
            [
                "module" => $this->module,
                "table" => $this->table,
                "moduleLower" => $this->moduleLower,
            ]
        );
    }
}
