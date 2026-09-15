<?php

namespace TomatoPHP\FilamentPlugins\Services;

use Illuminate\Support\Str;
use TomatoPHP\ConsoleHelpers\Traits\HandleFiles;
use TomatoPHP\ConsoleHelpers\Traits\HandleStub;
use TomatoPHP\FilamentPlugins\Services\Traits\GenerateInfo;
use TomatoPHP\FilamentPlugins\Services\Traits\GenerateModule;
use TomatoPHP\FilamentPlugins\Services\Traits\GeneratePage;
use TomatoPHP\FilamentPlugins\Services\Traits\GenerateReadMe;
use TomatoPHP\FilamentPlugins\Services\Traits\MoveFiles;

class PluginGenerator
{
    use GenerateInfo;
    use GenerateModule;
    use GeneratePage;
    use GenerateReadMe;
    use HandleFiles;
    use HandleStub;
    use MoveFiles;

    public function __construct(
        private string $name,
        private ?string $description,
        public ?string $color = null,
        public ?string $icon = null,
        public ?string $stubPath = null,
        public ?string $title = null,
    ) {
        $this->title = $name;
        $this->name = Str::of($name)->camel()->ucfirst()->toString();
        $this->stubPath = __DIR__.'/../../stubs/';
        $this->publish = __DIR__.'/../../stubs/';
    }

    public function generate(): void
    {
        $this->generateModule();
        $this->generateReadMe();
        $this->generateInfo();
        $this->moveFiles();
        $this->generatePage();
    }
}
