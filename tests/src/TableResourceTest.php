<?php

use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Livewire\Livewire;
use TomatoPHP\FilamentPlugins\Models\Table;
use TomatoPHP\FilamentPlugins\Resources\TableResource;
use TomatoPHP\FilamentPlugins\Resources\TableResource\Pages\CreateTable;
use TomatoPHP\FilamentPlugins\Resources\TableResource\Pages\EditTable;
use TomatoPHP\FilamentPlugins\Resources\TableResource\Pages\ListTables;
use TomatoPHP\FilamentPlugins\Resources\TableResource\RelationManagers\TableColsRelationManager;
use TomatoPHP\FilamentPlugins\Tests\Models\User;
use TomatoPHP\FilamentPlugins\Tests\TestCase;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(User::factory()->create());
    TestCase::createModule('Blog');
});

function makeTable(array $attributes = []): Table
{
    $table = Table::create(array_merge(['module' => 'Blog', 'name' => 'posts'], $attributes));
    $table->tableCols()->create(['name' => 'id', 'type' => 'bigint', 'primary' => true, 'auto_increment' => true]);
    $table->tableCols()->create(['name' => 'title', 'type' => 'string', 'order' => 1]);

    return $table;
}

it('renders the list page', function () {
    makeTable();

    $this->get(TableResource::getUrl('index', ['module' => 'Blog']))->assertSuccessful();

    Livewire::withQueryParams(['module' => 'Blog']);

    livewire(ListTables::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords(Table::all());
});

it('redirects the list page back to the plugins page without a module', function () {
    $this->get(TableResource::getUrl('index'))->assertRedirect();
});

it('renders the create page', function () {
    $this->get(TableResource::getUrl('create', ['module' => 'Blog']))->assertSuccessful();
});

it('creates a table with an id column', function () {
    Livewire::withQueryParams(['module' => 'Blog']);

    livewire(CreateTable::class)
        ->fillForm([
            'name' => 'comments',
            'timestamps' => true,
            'soft_deletes' => false,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $table = Table::where('name', 'comments')->firstOrFail();

    expect($table->module)->toBe('Blog')
        ->and($table->tableCols()->pluck('name')->all())->toBe(['id']);
});

it('renders the edit page and saves changes', function () {
    $table = makeTable();

    $this->get(TableResource::getUrl('edit', ['record' => $table]))->assertSuccessful();

    livewire(EditTable::class, ['record' => $table->getRouteKey()])
        ->assertSchemaStateSet(['name' => 'posts'])
        ->fillForm(['name' => 'articles', 'soft_deletes' => true])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($table->refresh())
        ->name->toBe('articles')
        ->soft_deletes->toBeTrue();
});

it('renders the table columns relation manager', function () {
    $table = makeTable();

    livewire(TableColsRelationManager::class, [
        'ownerRecord' => $table,
        'pageClass' => EditTable::class,
    ])
        ->assertSuccessful()
        ->assertCanSeeTableRecords($table->tableCols);
});

it('adds a column from the relation manager', function () {
    $table = makeTable();

    livewire(TableColsRelationManager::class, [
        'ownerRecord' => $table,
        'pageClass' => EditTable::class,
    ])
        ->callAction(TestAction::make('create')->table(), [
            'name' => 'body',
            'type' => 'text',
        ])
        ->assertHasNoFormErrors();

    expect($table->tableCols()->where('name', 'body')->exists())->toBeTrue();
});

it('is forbidden when the generator is turned off', function () {
    Filament::getCurrentOrDefaultPanel()->getPlugin('filament-plugins')->allowGenerator(false);

    $this->get(TableResource::getUrl('index', ['module' => 'Blog']))->assertForbidden();
});
