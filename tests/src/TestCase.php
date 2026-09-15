<?php

namespace TomatoPHP\FilamentPlugins\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Panel;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Livewire\LivewireServiceProvider;
use Nwidart\Modules\Activators\FileActivator;
use Nwidart\Modules\LaravelModulesServiceProvider;
use Orchestra\Testbench\Attributes\WithEnv;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;
use TomatoPHP\FilamentIcons\FilamentIconsServiceProvider;
use TomatoPHP\FilamentPlugins\FilamentPluginsServiceProvider;
use TomatoPHP\FilamentPlugins\Tests\Models\User;

#[WithEnv('DB_CONNECTION', 'testing')]
abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;
    use WithWorkbench;

    public ?Panel $panel;

    /**
     * Every test gets its own throw-away modules directory outside the package tree.
     */
    public static function modulesPath(): string
    {
        return sys_get_temp_dir().DIRECTORY_SEPARATOR.'filament-plugins-tests-'.getmypid().DIRECTORY_SEPARATOR.'Modules';
    }

    protected function setUp(): void
    {
        $files = new Filesystem;
        $files->deleteDirectory(dirname(static::modulesPath()));
        $files->ensureDirectoryExists(static::modulesPath());

        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        (new Filesystem)->deleteDirectory(dirname(static::modulesPath()));
    }

    /**
     * Write a plugin module (module.json + app folder) into the temporary modules path.
     *
     * @param  array<string, mixed>  $overrides
     */
    public static function createModule(string $name, array $overrides = []): string
    {
        $path = static::modulesPath().DIRECTORY_SEPARATOR.$name;

        File::ensureDirectoryExists($path.'/app/Models');
        File::ensureDirectoryExists($path.'/resources/views');
        File::put($path.'/module.json', json_encode(array_merge([
            'name' => $name,
            'alias' => strtolower($name),
            'title' => ['en' => $name.' Plugin'],
            'description' => ['en' => $name.' description'],
            'keywords' => [],
            'priority' => 0,
            'providers' => [],
            'files' => [],
            'color' => '#007dff',
            'icon' => 'heroicon-o-puzzle-piece',
            'placeholder' => 'placeholder.webp',
            'type' => 'plugin',
            'version' => 'v1.0',
        ], $overrides), JSON_PRETTY_PRINT));

        return $path;
    }

    /**
     * Make the classes generated inside a temporary module autoloadable.
     */
    public static function autoloadModule(string $name): void
    {
        $loader = require __DIR__.'/../../vendor/autoload.php';
        $loader->addPsr4("Modules\\{$name}\\", static::modulesPath().DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR.'app');
    }

    protected function getPackageProviders($app): array
    {
        $providers = [
            ActionsServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            SchemasServiceProvider::class,
            InfolistsServiceProvider::class,
            LivewireServiceProvider::class,
            NotificationsServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            LaravelModulesServiceProvider::class,
            FilamentIconsServiceProvider::class,
            FilamentPluginsServiceProvider::class,
            AdminPanelProvider::class,
        ];

        sort($providers);

        return $providers;
    }

    protected function defineEnvironment($app)
    {
        tap($app['config'], function (Repository $config) {
            $config->set('database.default', 'testing');
            $config->set('database.connections.testing', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]);

            $config->set('auth.guards.testing.driver', 'session');
            $config->set('auth.guards.testing.provider', 'testing');
            $config->set('auth.providers.testing.driver', 'eloquent');
            $config->set('auth.providers.testing.model', User::class);

            $config->set('modules.namespace', 'Modules');
            $config->set('modules.paths.modules', static::modulesPath());
            $config->set('modules.scan.enabled', false);
            $config->set('modules.cache.enabled', false);
            $config->set('modules.activator', 'file');
            $config->set('modules.activators.file', [
                'class' => FileActivator::class,
                'statuses-file' => dirname(static::modulesPath()).DIRECTORY_SEPARATOR.'modules_statuses.json',
                'cache-key' => 'activator.installed',
                'cache-lifetime' => 604800,
            ]);
        });
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadLaravelMigrations();
    }
}
