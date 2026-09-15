<?php

namespace TomatoPHP\FilamentPlugins\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Nwidart\Modules\Facades\Module as Modules;
use Nwidart\Modules\Module;
use Symfony\Component\Process\Process;
use TomatoPHP\ConsoleHelpers\Traits\RunCommand;
use TomatoPHP\FilamentPlugins\Services\ModulePaths;

use function Laravel\Prompts\search;

class FilamentPublishModule extends Command
{
    use RunCommand;

    protected ?Module $module = null;

    protected ?string $newPath = null;

    protected ?string $oldPath = null;

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

    public function handle(): int
    {
        $modules = collect(Modules::all())->filter(fn ($item) => str($item->getPath())->contains('vendor'));

        $module = $this->argument('module') && $this->argument('module') != '0' ? $this->argument('module') : search(
            label: 'Please input your module name you went to publish?',
            options: fn (string $value) => strlen($value) > 0
                ? $modules->filter(fn ($item) => str($item->getName())->contains($value))
                    ->map(fn ($item) => (string) $item->getName())
                    ->toArray()
                : [],
            placeholder: 'ex: FilamentAccounts',
            scroll: 10
        );

        $this->module = Modules::find($module);

        if (! $this->module) {
            $this->error("Module [{$module}] not found.");

            return static::FAILURE;
        }

        $this->info("Publishing module: {$module}");

        $this->moveFolder();
        $this->registerProvider();
        $this->updateComposer();

        $this->info("Module: {$module} published successfully");

        return static::SUCCESS;
    }

    public function moveFolder(): void
    {
        $this->oldPath = $this->module->getPath();
        $this->newPath = ModulePaths::modulesPath($this->module->getStudlyName());

        File::ensureDirectoryExists(dirname($this->newPath));
        File::moveDirectory($this->oldPath, $this->newPath);
    }

    /**
     * Remove the package from the host composer.json and run `composer update`.
     */
    public function updateComposer(): void
    {
        $composerJson = json_decode(File::get(base_path('composer.json')), true);
        $packageName = (string) str(str_replace('\\', '/', (string) $this->oldPath))->after('/vendor/');
        $composerJson['require'] = collect($composerJson['require'] ?? [])
            ->reject(fn ($version, $key) => $packageName !== '' && str($key)->contains($packageName))
            ->toArray();

        File::put(base_path('composer.json'), json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        (new Process(['composer', 'update'], base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(fn ($type, $output) => $this->output->write($output));
    }

    /**
     * Add the module providers to the host bootstrap/providers.php.
     */
    public function registerProvider(): void
    {
        $info = json_decode(File::get($this->newPath.'/module.json'));
        $providers = include base_path('bootstrap/providers.php');
        foreach ($info->providers ?? [] as $provider) {
            $providers[] = $provider;
        }

        $array = '';
        foreach (array_unique($providers) as $provider) {
            $array .= "\t".$provider."::class,\n";
        }
        File::put(base_path('bootstrap/providers.php'), "<?php\nreturn [\n ".$array." \n];\n");
    }
}
