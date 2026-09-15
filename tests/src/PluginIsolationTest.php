<?php

use Acme\Alpha\AlphaPage;
use Acme\Alpha\AlphaPlugin;
use Acme\Beta\BetaPlugin;
use Acme\Beta\BetaResource;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Support\Facades\Route;
use Modules\Gamma\GammaPage;
use Modules\Gamma\GammaPlugin;
use Nwidart\Modules\Facades\Module;
use TomatoPHP\FilamentPlugins\FilamentPluginsPlugin;
use TomatoPHP\FilamentPlugins\Tests\TestCase;

/**
 * Every TomatoPHP package ships a module.json and `filament-plugins:install` enables scanning vendor/,
 * so vendor packages show up as (never enabled) nwidart modules. Their plugins must stay untouched.
 */
it('keeps the pages, resources and routes of the other plugins on the panel', function () {
    expect(Module::find('Alpha'))->not->toBeNull()
        ->and(Module::find('Alpha')->isEnabled())->toBeFalse()
        ->and(Module::find('Beta')->isEnabled())->toBeFalse();

    $panel = Filament::getPanel('crowded');

    expect($panel->hasPlugin('acme-alpha'))->toBeTrue()
        ->and($panel->hasPlugin('acme-beta'))->toBeTrue()
        ->and($panel->getPages())->toContain(AlphaPage::class)
        ->and($panel->getResources())->toContain(BetaResource::class)
        ->and(Route::has('filament.crowded.pages.alpha-page'))->toBeTrue()
        ->and(Route::has('filament.crowded.resources.betas.index'))->toBeTrue()
        ->and(Route::has('filament.crowded.pages.plugins'))->toBeTrue();
});

it('disables only the plugin that belongs to a disabled module', function () {
    TestCase::createModule('Gamma');

    expect(Module::find('Gamma')->isEnabled())->toBeFalse();

    $panel = Panel::make()->id('gamma')->plugins([
        GammaPlugin::make(),
        AlphaPlugin::make(),
        BetaPlugin::make(),
        FilamentPluginsPlugin::make(),
    ]);

    expect($panel->hasPlugin('modules-gamma'))->toBeFalse()
        ->and($panel->getPages())->not->toContain(GammaPage::class)
        ->and($panel->getPages())->toContain(AlphaPage::class)
        ->and($panel->getResources())->toContain(BetaResource::class);
});

it('keeps the plugin of an enabled module', function () {
    TestCase::createModule('Gamma');
    Module::find('Gamma')->enable();

    $panel = Panel::make()->id('gamma-enabled')->plugins([
        GammaPlugin::make(),
        FilamentPluginsPlugin::make(),
    ]);

    expect($panel->hasPlugin('modules-gamma'))->toBeTrue()
        ->and($panel->getPages())->toContain(GammaPage::class);
});
