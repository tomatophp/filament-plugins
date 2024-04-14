<?php

namespace TomatoPHP\FilamentPlugins\Services\Traits;

use Nwidart\Modules\Facades\Module;

trait GeneratePage
{
    public function generatePage(): void
    {
        $module = Module::find($this->name);
        $this->generateStubs(
            $this->stubPath . 'page.stub',
            base_path("Modules") . '/'. $this->name . '/app/Filament/Pages/'. $this->name . 'Page.php',
            [
                "namespace" => "Modules\\" . $this->name . "\\Filament\\Pages",
                "view" => $module->getLowerName()."::index",
                "title" => $this->title,
                "icon" => $this->icon,
                "name" => $this->name . 'Page',
            ],
            [
                base_path("Modules") . "/". $this->name . "/app/Filament",
                base_path("Modules") . "/". $this->name . "/app/Filament/Pages",
            ]
        );
    }
}
