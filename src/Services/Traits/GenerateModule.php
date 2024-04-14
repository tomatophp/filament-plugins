<?php

namespace TomatoPHP\TomatoPlugins\Services\Traits;

use Illuminate\Support\Facades\Artisan;

trait GenerateModule
{
    public function generateModule()
    {
        Artisan::call('module:make ' . $this->name);
        sleep(3);
    }
}
