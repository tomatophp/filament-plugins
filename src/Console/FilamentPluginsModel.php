<?php

namespace TomatoPHP\FilamentPlugins\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;
use TomatoPHP\ConsoleHelpers\Traits\RunCommand;
use TomatoPHP\FilamentPlugins\Services\CRUDGenerator;
use TomatoPHP\FilamentPlugins\Services\PluginGenerator;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\search;
use function Laravel\Prompts\suggest;
use function Laravel\Prompts\text;

class FilamentPluginsModel extends Command
{
    use RunCommand;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filament-plugins:model {table?} {module?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate a new model';

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
        $tables = collect(\DB::select('SHOW TABLES'))->map(function ($item){
            return $item->{'Tables_in_'.config('database.connections.mysql.database')};
        })->toArray();

        $tableName = $this->argument('table') && $this->argument('table') != "0" ? $this->argument('table') : search(
            label: 'Please input your table name you went to create CRUD?',
            options: fn (string $value) => strlen($value) > 0
                ? collect($tables)->filter(function ($item, $key) use ($value){
                    return Str::contains($item, $value) ? (string)$item : null;
                })->toArray()
                : [],
            placeholder: "ex: users",
            scroll: 10
        );

        if(is_numeric($tableName)){
            $tableName = $tables[$tableName];
        }
        else {
            $tableName = $tableName;
        }

        //Check if user need to use HMVC
        $isModule = ($this->argument('module') && $this->argument('module') != "0") ?: confirm('Do you went to use HMVC module?');
        $moduleName = false;
        if ($isModule){
            if (class_exists(\Nwidart\Modules\Facades\Module::class)){
                $modules = \Nwidart\Modules\Facades\Module::toCollection()->map(function ($item){
                    return $item->getName();
                });
                $moduleName = ($this->argument('module') && $this->argument('module') != "0") ? $this->argument('module') : suggest(
                    label:'Please input your module name?',
                    placeholder:'Translations',
                    options: fn (string $value) => strlen($value) > 0
                        ? collect($modules)->filter(function ($item, $key) use ($value){
                            return Str::contains($item, $value) ? $item : null;
                        })->toArray()
                        : [],
                    validate: fn (string $value) => match (true) {
                        strlen($value) < 1 => "Sorry this filed is required!",
                        default => null
                    },
                    scroll: 10
                );
                $check = \Nwidart\Modules\Facades\Module::find($moduleName);
                if (!$check) {
                    $createIt = confirm('Module not found! do you when to create it?');
                    $createIt ? $this->artisanCommand(["module:make", $moduleName]) : $moduleName = null;
                    \Laravel\Prompts\info('We Generate It please re-run the command again');
                    exit();
                }
            }
            else {
                $installItem = confirm('Sorry nwidart/laravel-modules not installed please install it first. do you when to install it?');
                if($installItem){
                    $this->requireComposerPackages(["nwidart/laravel-modules"]);
                    \Laravel\Prompts\info('Add This line to composer.json psr-4 autoload');
                    \Laravel\Prompts\info('"Modules\\" : "Modules/"');
                    \Laravel\Prompts\info('now run');
                    \Laravel\Prompts\info('composer dump-autoload');
                    \Laravel\Prompts\info('Install success please run the command again');
                    exit();
                }
            }
        }

        $generator = new CRUDGenerator(
            tableName: $tableName,
            moduleName: $moduleName,
            migration: false,
            models: true
        );

        $generator->generate();

        $this->info('Model generated successfully.');
    }
}
