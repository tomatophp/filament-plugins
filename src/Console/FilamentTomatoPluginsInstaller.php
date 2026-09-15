<?php

namespace TomatoPHP\FilamentPlugins\Console;

use Illuminate\Console\Command;
use TomatoPHP\ConsoleHelpers\Traits\RunCommand;
use TomatoPHP\FilamentPlugins\Console\Contracts\Plugin;
use TomatoPHP\FilamentPlugins\Console\Contracts\PluginsList;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\select;

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

    public function handle(): int
    {
        $all = confirm(
            label: 'Do you want to install all plugins?',
            default: false,
        );

        if (! $all) {
            $group = select(
                label: 'Select the group of plugins you want to install',
                options: PluginsList::make()->groupBy('group')->keys()->toArray(),
                required: true,
            );

            $packages = multiselect(
                label: 'Select the package you want to install',
                options: PluginsList::make()->where('group', $group)->sortBy('group')->pluck('label', 'key')->toArray(),
                required: true,
            );

            foreach ($packages as $package) {
                $this->installPlugin(PluginsList::make()->where('key', $package)->first());
            }
        } else {
            foreach (PluginsList::make() as $package) {
                $this->installPlugin($package);
            }
        }

        info('Thanks for using Tomato Plugins & TomatoPHP framework');
        info('Join support server on discord https://discord.gg/VZc8nBJ3ZU');
        info('You can check docs here https://docs.tomatophp.com');
        info('Please give us a star on any repo if you like it https://github.com/tomatophp');
        info('Sponsor us here https://github.com/sponsors/3x1io');

        return static::SUCCESS;
    }

    /**
     * Runs `composer require` and the plugin install command.
     */
    protected function installPlugin(Plugin $plugin): void
    {
        $plugin->install();
    }
}
