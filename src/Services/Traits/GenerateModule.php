<?php

namespace TomatoPHP\FilamentPlugins\Services\Traits;

use Illuminate\Support\Facades\Artisan;

trait GenerateModule
{
    public function generateModule(): void
    {
        Artisan::call('module:make', ['name' => [$this->name]]);
    }
}
