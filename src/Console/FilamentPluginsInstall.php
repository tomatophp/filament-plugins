<?php

namespace TomatoPHP\FilamentPlugins\Console;

use Illuminate\Console\Command;
use TomatoPHP\ConsoleHelpers\Traits\RunCommand;

class FilamentPluginsInstall extends Command
{
    use RunCommand;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'filament-plugins:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'install package and publish assets';

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
        $this->info('Publish Vendor Assets');
        $this->artisanCommand(["migrate"]);
        $this->artisanCommand(["optimize:clear"]);
        $this->info('Filament Plugins installed successfully.');
    }
}
