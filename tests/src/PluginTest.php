<?php

use Filament\Facades\Filament;
use Filament\Panel;
use TomatoPHP\FilamentPlugins\FilamentPluginsPlugin;
use TomatoPHP\FilamentPlugins\Pages\Plugins;
use TomatoPHP\FilamentPlugins\Resources\TableResource;

it('registers the plugin on the panel', function () {
    $panel = Filament::getCurrentOrDefaultPanel();

    expect($panel->getPlugin('filament-plugins'))->toBeInstanceOf(FilamentPluginsPlugin::class)
        ->and($panel->getPages())->toContain(Plugins::class)
        ->and($panel->getResources())->toContain(TableResource::class);
});

it('allows every file-writing feature by default', function () {
    $plugin = FilamentPluginsPlugin::make();

    foreach (array_keys(FilamentPluginsPlugin::FEATURES) as $feature) {
        expect($plugin->isAllowed($feature))->toBeTrue();
    }
});

it('falls back to the config file when an option is not set', function () {
    config()->set('filament-plugins.allow_upload', false);

    expect(FilamentPluginsPlugin::make()->isAllowed('import'))->toBeFalse()
        ->and(FilamentPluginsPlugin::make()->allowImport()->isAllowed('import'))->toBeTrue();
});

it('can turn every file-writing feature off', function () {
    $plugin = FilamentPluginsPlugin::make()
        ->allowCreate(false)
        ->allowImport(false)
        ->allowToggle(false)
        ->allowDestroy(false)
        ->allowGenerator(false);

    foreach (array_keys(FilamentPluginsPlugin::FEATURES) as $feature) {
        expect($plugin->isAllowed($feature))->toBeFalse();
    }
});

it('does not register the tables builder when the generator is disabled', function () {
    $panel = Panel::make()->id('locked');

    FilamentPluginsPlugin::make()->allowGenerator(false)->register($panel);

    expect($panel->getPages())->toContain(Plugins::class)
        ->and($panel->getResources())->not->toContain(TableResource::class);
});
