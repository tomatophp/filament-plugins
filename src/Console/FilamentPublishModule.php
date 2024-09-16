<?php

namespace TomatoPHP\FilamentPlugins\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Nwidart\Modules\Module;
use TomatoPHP\ConsoleHelpers\Traits\RunCommand;
use TomatoPHP\FilamentPlugins\Services\PublishPackage;
use function Laravel\Prompts\search;

class FilamentPublishModule extends Command
{
    use RunCommand;

    private ?Module $module = null;
    private ?string $newPath = null;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filament-plugins:publish {module?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'publish a module';

    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $modules = collect(\Nwidart\Modules\Facades\Module::all())->filter(function ($item){
            if(str($item->getPath())->contains('vendor')){
                return true;
            }

            return false;
        });
        $module = $this->argument('module') && $this->argument('module') != "0" ? $this->argument('module') : search(
            label: 'Please input your module name you went to publish?',
            options: fn (string $value) => strlen($value) > 0
                ? $modules->filter(function ($item, $key) use ($value){
                    return str($item->getName())->contains($value) ? (string)$item->getName() : null;
                })->toArray()
                : [],
            placeholder: "ex: FilamentAccounts",
            scroll: 10
        );

        $this->module = \Nwidart\Modules\Facades\Module::find($module);

        $this->info("Publishing module: {$module}");

        $this->moveFolder();
        $this->registerProvider();
        $this->updateComposer();


        $this->info("Module: {$module} published successfully");
    }


    public function moveFolder()
    {
        $basePath = $this->module->getPath();
        $newFolderName = $this->module->getStudlyName();

        File::move($basePath, base_path("Modules/{$newFolderName}"));

        $this->newPath = base_path("Modules/{$newFolderName}");
    }

    public function updateComposer()
    {
        $composerJson = json_decode(File::get(base_path('composer.json')), true);
        $packageName = str($this->module->getPath())->remove(base_path('/vendor/'));
        $composerCollect = collect($composerJson['require'])->filter(function ($item, $key) use ($packageName){
            return !str($key)->contains($packageName);
        });
        $composerJson['require'] = $composerCollect->toArray();

        File::put(base_path('composer.json'), json_encode($composerJson, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES ));

        system('composer update');
    }

    public function registerProvider()
    {
        $info = json_decode(File::get($this->newPath . "/module.json"));
        $providers = include base_path('bootstrap/providers.php');
        foreach ($info->providers as $provider){
            $providers[] = $provider;
        }

        $array = "";
        foreach ($providers as $provider){
            $array .= "\t".$provider."::class,\n";
        }
        File::put(base_path('bootstrap/providers.php'), "<?php\nreturn [\n ".$array." \n];\n");
    }

}
