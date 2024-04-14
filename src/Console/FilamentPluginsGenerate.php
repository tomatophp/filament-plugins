<?php

namespace TomatoPHP\FilamentPlugins\Console;

use Illuminate\Console\Command;
use Nwidart\Modules\Facades\Module;
use TomatoPHP\ConsoleHelpers\Traits\RunCommand;
use TomatoPHP\FilamentPlugins\Services\PluginGenerator;
use function Laravel\Prompts\error;
use function Laravel\Prompts\text;

class FilamentPluginsGenerate extends Command
{
    use RunCommand;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filament-plugins:generate {name?} {description?} {icon?} {color?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate a new plugin';

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name') ?? text(label: 'What is the name of the plugin?', required: true);
        while(Module::find($name)){
            error('Sorry Plugin already exists.');
            $name = $this->argument('name') ?? text(label: 'What is the name of the plugin?', required: true);
        }

        $description = $this->argument('description') ?? text(label: 'What is the description of the plugin?',required: true);
        $icon = $this->argument('icon') ?? text(label: 'What is the icon of the plugin?', placeholder: 'heroicon-o-cog', required: true);
        $color = $this->argument('color') ?? text(label: 'What is the color of the plugin?', placeholder: '#fefefe', required: true);

        $pluginGenerator = new PluginGenerator(
            name: $name,
            description: $description,
            icon: $icon,
            color: $color
        );
        $pluginGenerator->generate();

        $this->info('Plugin generated successfully.');
    }
}
