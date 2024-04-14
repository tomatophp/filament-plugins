<?php

namespace TomatoPHP\TomatoPlugins\Services\Concerns\Flutter;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait GenerateConfig
{
    private function generateConfig(): void
    {
        $appConfigPath = base_path('/flutter/' . $this->appName . '/lib/config/Config.dart');

        //Delete App Service If Exists
        if (File::exists($appConfigPath)) {
            File::delete($appConfigPath);
        }
        //Create App Service
        $this->generateStubs(
            $this->stubPath . "/config/Config.stub",
            $appConfigPath,
            [
                "app_name" => $this->appName,
                "url" => url('/api'),
            ]
        );
    }
}
