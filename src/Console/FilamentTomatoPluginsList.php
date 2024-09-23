<?php

namespace TomatoPHP\FilamentPlugins\Console;

use Illuminate\Console\Command;
use TomatoPHP\ConsoleHelpers\Traits\RunCommand;
use TomatoPHP\FilamentPlugins\Console\Contracts\PluginsList;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\select;
use function Laravel\Prompts\suggest;

class FilamentTomatoPluginsList extends Command
{
    use RunCommand;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filament-plugins:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all available plugins';

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
        $plugins = PluginsList::make();

        foreach ($plugins as $plugin){
            $this->warn($plugin->label);
            $this->info($plugin->description);
            $this->info('Docs:'.$plugin->url);
            $this->warn('Install: composer required tomatophp/'.$plugin->key);
            $this->info("====================================");
        }
    }
}
