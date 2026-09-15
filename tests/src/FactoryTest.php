<?php

use TomatoPHP\FilamentPlugins\Models\Table;
use TomatoPHP\FilamentPlugins\Models\TableCol;
use TomatoPHP\FilamentPlugins\Resources\TableResource;
use TomatoPHP\FilamentPlugins\Resources\TableResource\Pages\EditTable;
use TomatoPHP\FilamentPlugins\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

it('creates a builder table from the factory', function () {
    $table = Table::factory()->create();

    expect($table->name)->toMatch('/^[a-z][a-z0-9_]*$/')
        ->and($table->canMigrate())->toBeTrue()
        ->and($table->tableCols()->pluck('name')->all())->toBe(['id']);
});

it('creates table columns from the factory', function () {
    $column = TableCol::factory()->create();

    expect($column->table)->toBeInstanceOf(Table::class);
});

it('renders the edit page for a factory table', function () {
    actingAs(User::factory()->create());

    $table = Table::factory()->create();

    $this->get(TableResource::getUrl('edit', ['record' => $table]))->assertSuccessful();

    livewire(EditTable::class, ['record' => $table->getRouteKey()])
        ->assertSuccessful()
        ->assertSchemaStateSet(['name' => $table->name]);
});
