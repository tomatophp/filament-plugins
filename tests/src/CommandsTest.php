<?php

use Illuminate\Support\Facades\File;
use TomatoPHP\FilamentPlugins\Console\Contracts\Plugin;
use TomatoPHP\FilamentPlugins\Console\Contracts\PluginsList;
use TomatoPHP\FilamentPlugins\Console\FilamentPluginsInstall;
use TomatoPHP\FilamentPlugins\Console\FilamentPublishModule;
use TomatoPHP\FilamentPlugins\Console\FilamentTomatoPluginsInstaller;
use TomatoPHP\FilamentPlugins\Tests\TestCase;

use function Pest\Laravel\artisan;

it('runs the install command', function () {
    // The real command shells out to `php artisan migrate`; skip the sub-processes in tests.
    app()->bind(FilamentPluginsInstall::class, fn () => new class extends FilamentPluginsInstall
    {
        public function artisanCommand(array $command, ?bool $withOutput = false): void
        {
            //
        }
    });

    artisan('filament-plugins:install')
        ->expectsOutputToContain('Filament Plugins installed successfully.')
        ->assertSuccessful();

    expect(config_path('modules.php'))->toBeFile();

    File::delete(config_path('modules.php'));
});

it('clears instead of builds the filament component cache on install', function () {
    // `filament:optimize` froze the panel components at install time, so every plugin registered
    // afterwards disappeared from the panel until the cache was cleared by hand.
    $ran = new ArrayObject;

    app()->bind(FilamentPluginsInstall::class, fn () => new class($ran) extends FilamentPluginsInstall
    {
        public function __construct(private ArrayObject $ran)
        {
            parent::__construct();
        }

        public function artisanCommand(array $command, ?bool $withOutput = false): void
        {
            $this->ran[] = implode(' ', $command);
        }
    });

    artisan('filament-plugins:install')->assertSuccessful();

    expect($ran->getArrayCopy())
        ->toContain('filament:optimize-clear')
        ->not->toContain('filament:optimize');

    File::delete(config_path('modules.php'));
});

it('lists the TomatoPHP plugins', function () {
    artisan('filament-plugins:list')
        ->expectsOutputToContain('User Manager')
        ->expectsOutputToContain('tomatophp/filament-users')
        ->assertSuccessful();
});

it('installs the selected TomatoPHP plugins', function () {
    // The real installer runs `composer require` and artisan sub-processes; record the plugins instead.
    $installed = new ArrayObject;

    app()->bind(FilamentTomatoPluginsInstaller::class, fn () => new class($installed) extends FilamentTomatoPluginsInstaller
    {
        public function __construct(private ArrayObject $installed)
        {
            parent::__construct();
        }

        protected function installPlugin(Plugin $plugin): void
        {
            $this->installed[] = $plugin->key;
        }
    });

    artisan('filament:plugins')
        ->expectsConfirmation('Do you want to install all plugins?', 'yes')
        ->assertSuccessful();

    expect($installed->getArrayCopy())->toBe(PluginsList::make()->pluck('key')->all());
});

it('publishes a vendor module into the modules folder', function () {
    $vendorModule = dirname(TestCase::modulesPath()).'/vendor/acme/blog-module';
    File::ensureDirectoryExists($vendorModule);
    File::put($vendorModule.'/module.json', json_encode([
        'name' => 'AcmeBlog',
        'alias' => 'acme-blog',
        'providers' => [],
    ]));

    config()->set('modules.scan.enabled', true);
    config()->set('modules.scan.paths', [dirname(TestCase::modulesPath()).'/vendor/*/*']);

    // Registering the provider and running `composer update` touch the host app; record them instead.
    $calls = new ArrayObject;

    app()->bind(FilamentPublishModule::class, fn () => new class($calls) extends FilamentPublishModule
    {
        public function __construct(private ArrayObject $calls)
        {
            parent::__construct();
        }

        public function registerProvider(): void
        {
            $this->calls[] = 'registerProvider';
        }

        public function updateComposer(): void
        {
            $this->calls[] = 'updateComposer';
        }
    });

    artisan('filament-plugins:publish', ['module' => 'AcmeBlog'])
        ->expectsOutputToContain('published successfully')
        ->assertSuccessful();

    expect(TestCase::modulesPath().'/AcmeBlog/module.json')->toBeFile()
        ->and($vendorModule)->not->toBeDirectory()
        ->and($calls->getArrayCopy())->toBe(['registerProvider', 'updateComposer']);
});
