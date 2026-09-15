<?php

use Filament\Actions\Action;
use Filament\Actions\Testing\TestAction;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use TomatoPHP\FilamentPlugins\Models\Table;
use TomatoPHP\FilamentPlugins\Resources\TableResource\Pages\CreateTable;
use TomatoPHP\FilamentPlugins\Resources\TableResource\Pages\ListTables;
use TomatoPHP\FilamentPlugins\Tests\Models\User;
use TomatoPHP\FilamentPlugins\Tests\TestCase;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

/**
 * The migrate action drops the database table, so it must never touch a table the builder did not create.
 * Generated migrations are written into TestCase::modulesPath(), a temporary folder.
 */
beforeEach(function () {
    actingAs(User::factory()->create());
    $this->module = TestCase::createModule('Blog');
});

function builderTable(string $name): Table
{
    $table = Table::create(['module' => 'Blog', 'name' => $name, 'timestamps' => true]);
    $table->tableCols()->create(['name' => 'id', 'type' => 'bigint', 'primary' => true]);
    $table->tableCols()->create(['name' => 'title', 'type' => 'string', 'order' => 1]);

    return $table;
}

function runModuleMigrations(string $module): void
{
    Artisan::call('migrate', [
        '--path' => $module.'/database/migrations',
        '--realpath' => true,
        '--force' => true,
    ]);
}

it('refuses to migrate a builder table named after an existing table it does not own', function () {
    User::factory()->count(2)->create();
    $users = User::count();
    $table = builderTable('users');

    Livewire::withQueryParams(['module' => 'Blog']);

    livewire(ListTables::class)
        ->callAction(TestAction::make('migrate')->table($table))
        ->assertNotified(trans('filament-plugins::messages.tables.notifications.not-owned.title'));

    expect(Schema::hasTable('users'))->toBeTrue()
        ->and(User::count())->toBe($users)
        ->and(File::glob($this->module.'/database/migrations/*_create_users_table.php'))->toBeEmpty()
        ->and($table->refresh()->migrated_at)->toBeNull();
});

it('migrates and re-migrates a table the builder owns', function () {
    $table = builderTable('posts');

    expect($table->migrate())->toBeTrue();
    runModuleMigrations($this->module);

    expect(Schema::hasTable('posts'))->toBeTrue()
        ->and($table->refresh()->ownsDatabaseTable())->toBeTrue();

    DB::table('posts')->insert(['title' => 'Hello']);

    expect($table->migrate())->toBeTrue()
        ->and(Schema::hasTable('posts'))->toBeFalse();

    runModuleMigrations($this->module);

    expect(Schema::hasTable('posts'))->toBeTrue()
        ->and(DB::table('posts')->count())->toBe(0);
});

it('rejects existing database tables and non snake_case names', function (string $name) {
    Livewire::withQueryParams(['module' => 'Blog']);

    livewire(CreateTable::class)
        ->fillForm(['name' => $name])
        ->call('create')
        ->assertHasFormErrors(['name']);

    expect(Table::where('name', $name)->exists())->toBeFalse();
})->with(['users', 'tables', 'BlogPosts', 'blog-posts', '1posts']);

it('asks for confirmation before migrating and warns that the table is dropped and recreated', function () {
    $table = builderTable('posts');

    Livewire::withQueryParams(['module' => 'Blog']);

    livewire(ListTables::class)
        ->assertActionExists(
            TestAction::make('migrate')->table($table),
            fn (Action $action): bool => $action->isConfirmationRequired()
                && str_contains((string) $action->getModalDescription(), 'drops')
                && str_contains((string) $action->getModalDescription(), 'recreates'),
        );
});
