<?php

use Filament\Facades\Filament;
use Nwidart\Modules\Facades\Module;
use TomatoPHP\FilamentPlugins\Models\Plugin;
use TomatoPHP\FilamentPlugins\Pages\Plugins;
use TomatoPHP\FilamentPlugins\Tests\Models\User;
use TomatoPHP\FilamentPlugins\Tests\TestCase;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(User::factory()->create());
});

function refreshPluginRows(): void
{
    Module::scan();
    Plugin::clearBootedModels();
}

it('renders the plugins page without modules', function () {
    $this->get(Plugins::getUrl())->assertSuccessful();

    livewire(Plugins::class)->assertSuccessful();
});

it('renders the plugins page with modules', function () {
    TestCase::createModule('Blog');
    refreshPluginRows();

    $this->get(Plugins::getUrl())
        ->assertSuccessful()
        ->assertSee('Blog Plugin')
        ->assertSee('Blog description');

    livewire(Plugins::class)
        ->assertSuccessful()
        ->assertActionVisible('create')
        ->assertActionVisible('import')
        ->assertActionVisible('enable');
});

it('enables and disables a module', function () {
    TestCase::createModule('Blog');
    refreshPluginRows();

    livewire(Plugins::class)
        ->callAction('activeAction', arguments: ['module' => 'Blog', 'providers' => '[]']);

    expect(Module::find('Blog')->isEnabled())->toBeTrue();

    livewire(Plugins::class)
        ->callAction('disableAction', arguments: ['module' => 'Blog']);

    expect(Module::find('Blog')->isEnabled())->toBeFalse();
});

it('hides the file-writing actions when they are turned off', function () {
    TestCase::createModule('Blog');
    refreshPluginRows();

    Filament::getCurrentOrDefaultPanel()->getPlugin('filament-plugins')
        ->allowCreate(false)
        ->allowImport(false)
        ->allowToggle(false)
        ->allowDestroy(false)
        ->allowGenerator(false);

    livewire(Plugins::class)
        ->assertSuccessful()
        ->assertActionHidden('create')
        ->assertActionHidden('import')
        ->assertActionHidden('enable')
        ->assertActionHidden('disable')
        ->assertDontSee('resources/tables')
        ->assertActionHidden('activeAction', ['module' => 'Blog', 'providers' => '[]'])
        ->assertActionHidden('disableAction', ['module' => 'Blog'])
        ->assertActionHidden('deleteAction', ['module' => 'Blog']);

    expect(Module::find('Blog')->isEnabled())->toBeFalse();
});
