<?php

namespace TomatoPHP\FilamentPlugins\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use TomatoPHP\ConsoleHelpers\Traits\HandleFiles;
use TomatoPHP\ConsoleHelpers\Traits\HandleStub;
use TomatoPHP\FilamentPlugins\Services\Traits\GeneratePage;
use TomatoPHP\FilamentPlugins\Services\Traits\MoveFiles;
use TomatoPHP\TomatoSettings\Settings\ThemesSettings;
use TomatoPHP\FilamentPlugins\Services\Traits\GenerateInfo;
use TomatoPHP\FilamentPlugins\Services\Traits\GenerateModule;
use TomatoPHP\FilamentPlugins\Services\Traits\GenerateReadMe;

class PluginGenerator
{
    use HandleStub;
    use HandleFiles;
    use GenerateInfo;
    use GenerateReadMe;
    use GenerateModule;
    use GeneratePage;
    use MoveFiles;

    public function __construct(
        private string $name,
        private string|null $description,
        public string|null $color = null,
        public string|null $icon = null,
        public string|null $stubPath = null,
        public string|null $title = null,
    )
    {
        $this->title = $name;
        $this->name = Str::of($name)->camel()->ucfirst()->toString();
        $this->stubPath = __DIR__ . '/../../stubs/';
        $this->publish = __DIR__ . '/../../stubs/';
    }

    /**
     * @return void
     */
    public function generate(): void
    {
        $this->generateModule();
        $this->generateReadMe();
        $this->generateInfo();
        $this->moveFiles();
        $this->generatePage();
    }
}
