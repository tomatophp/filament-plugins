<?php

namespace TomatoPHP\TomatoPlugins\Services\Traits;

use Illuminate\Support\Str;

trait GenerateReadMe
{
    /**
     * @return void
     */
    private function generateReadMe(): void
    {
        //Generate Readme.md file
        $this->generateStubs(
            $this->stubPath . 'readme.stub',
            base_path("Modules") . '/'. $this->name . '/README.md',
            [
                "name" => $this->name,
                "title" => $this->title,
                "description" => $this->description,
            ],
            [
                base_path("Modules"),
                base_path("Modules") . "/". $this->name,
            ]
        );
    }
}
