<?php

namespace TomatoPHP\FilamentPlugins\Console;

use Illuminate\Console\Command;
use TomatoPHP\ConsoleHelpers\Traits\RunCommand;
use TomatoPHP\FilamentPlugins\Console\Contracts\PluginsList;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\select;
use function Laravel\Prompts\suggest;

class FilamentTomatoPluginsInstaller extends Command
{
    use RunCommand;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filament:plugins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'install selected TomatoPHP echo system plugins';

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
        $all = confirm(
            label: 'Do you want to install all plugins?',
            default: false,
        );

        if(!$all){
            $group = select(
                label: 'Select the group of plugins you want to install',
                options: PluginsList::make()->groupBy('group')->keys()->toArray(),
                required: true,
            );

            $packages = multiselect(
                label: 'Select the package you want to install',
                required: true,
                options: PluginsList::make()->where('group', $group)->sortBy('group')->pluck('label', 'key')->toArray(),
            );

            foreach ($packages as $package) {
                $package = PluginsList::make()->where('key', $package)->first();
                $package->install();
            }
        }
        else {
            $packages = PluginsList::make();
            foreach ($packages as $package){
                $package->install();
            }
        }

        \Laravel\Prompts\info('🍅 Thanks for using Tomato Plugins & TomatoPHP framework');
        \Laravel\Prompts\info('💼 Join support server on discord https://discord.gg/VZc8nBJ3ZU');
        \Laravel\Prompts\info('📄 You can check docs here https://docs.tomatophp.com');
        \Laravel\Prompts\info('⭐ please gave us a start on any repo if you like it https://github.com/tomatophp');
        \Laravel\Prompts\info('🤝 sponser us here https://github.com/sponsors/3x1io');
    }
}
