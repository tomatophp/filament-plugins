<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Process\Process;
use TomatoPHP\FilamentPlugins\Models\Table;
use TomatoPHP\FilamentPlugins\Tests\TestCase;

use function Pest\Laravel\artisan;

/**
 * Every generator writes into TestCase::modulesPath(), a temporary folder outside the package.
 */
function expectValidPhp(string $file): void
{
    expect($file)->toBeFile();

    $process = new Process([PHP_BINARY, '-l', $file]);
    $process->run();

    expect($process->isSuccessful())->toBeTrue($process->getOutput().$process->getErrorOutput());
}

function createPostsTable(): void
{
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('body')->nullable();
        $table->boolean('is_published')->default(false);
        $table->timestamps();
    });
}

beforeEach(function () {
    $this->module = TestCase::createModule('Blog');
    TestCase::autoloadModule('Blog');
});

it('never writes into the package tree', function () {
    expect(TestCase::modulesPath())->not->toStartWith(realpath(__DIR__.'/../..'));
});

it('generates a migration from the tables builder', function () {
    $table = Table::create(['module' => 'Blog', 'name' => 'posts', 'timestamps' => true]);
    $table->tableCols()->create(['name' => 'id', 'type' => 'bigint', 'primary' => true]);
    $table->tableCols()->create(['name' => 'title', 'type' => 'string', 'order' => 1]);

    $table->migrate();

    $migrations = File::files($this->module.'/database/migrations');

    expect($migrations)->toHaveCount(1)
        ->and($migrations[0]->getFilename())->toContain('_create_posts_table');

    $contents = File::get($migrations[0]->getPathname());

    expect($contents)
        ->toContain("Schema::create('posts'")
        ->toContain('$table->id()')
        ->toContain('$table->string("title")')
        ->toContain('$table->timestamps();');

    expectValidPhp($migrations[0]->getPathname());
});

it('generates a model for a table', function () {
    createPostsTable();

    artisan('filament-plugins:model', ['table' => 'posts', 'module' => 'Blog'])
        ->expectsOutputToContain('Model generated successfully.')
        ->assertSuccessful();

    $model = $this->module.'/app/Models/Post.php';

    expect(File::get($model))
        ->toContain('namespace Modules\\Blog\\Models;')
        ->toContain('class Post extends Model')
        ->toContain("'title'")
        ->toContain("'is_published' => 'boolean'");

    expectValidPhp($model);
});

it('generates a Filament v5 resource inside the module', function () {
    createPostsTable();
    artisan('filament-plugins:model', ['table' => 'posts', 'module' => 'Blog'])->assertSuccessful();

    artisan('filament-plugins:resource', [
        'model' => 'Post',
        'module' => 'Blog',
        '--generate' => true,
        '--record-title-attribute' => 'title',
        '--panel' => 'admin',
        '--no-interaction' => true,
    ])
        ->expectsConfirmation('Would you like to generate a read-only view page for the resource?', 'no')
        ->assertSuccessful();

    $directory = $this->module.'/app/Filament/Resources/Posts';
    $resource = File::get($directory.'/PostResource.php');
    $form = File::get($directory.'/Schemas/PostForm.php');
    $table = File::get($directory.'/Tables/PostsTable.php');

    expect($resource)
        ->toContain('namespace Modules\\Blog\\Filament\\Resources\\Posts;')
        ->toContain('use Modules\\Blog\\Models\\Post;')
        ->toContain('use Filament\\Schemas\\Schema;')
        ->toContain('public static function form(Schema $schema): Schema')
        ->toContain('BackedEnum')
        ->and($form)
        ->toContain('->components([')
        ->toContain("TextInput::make('title')")
        ->and($table)
        ->toContain('->recordActions([')
        ->toContain('->toolbarActions([')
        ->toContain('Filament\\Actions\\EditAction')
        ->not->toContain('Tables\\Actions');

    foreach (File::allFiles($directory) as $file) {
        expectValidPhp($file->getPathname());
    }
});

it('generates a Filament v5 page inside the module', function () {
    artisan('filament-plugins:page', [
        'name' => 'Reports',
        'module' => 'Blog',
        '--panel' => 'admin',
        '--no-interaction' => true,
    ])
        ->expectsConfirmation('Would you like to create this page in a resource?', 'no')
        ->assertSuccessful();

    $page = $this->module.'/app/Filament/Pages/Reports.php';

    expect(File::get($page))
        ->toContain('namespace Modules\\Blog\\Filament\\Pages;')
        ->toContain("protected string \$view = 'blog::filament.pages.reports';")
        ->and($this->module.'/resources/views/filament/pages/reports.blade.php')->toBeFile();

    expectValidPhp($page);
});

it('generates a Filament v5 widget inside the module', function () {
    artisan('filament-plugins:widget', [
        'name' => 'PostsOverview',
        'module' => 'Blog',
        '--stats-overview' => true,
        '--panel' => 'admin',
        '--no-interaction' => true,
    ])
        ->expectsConfirmation('Would you like to create this widget in a resource?', 'no')
        ->assertSuccessful();

    $widget = $this->module.'/app/Filament/Widgets/PostsOverview.php';

    expect(File::get($widget))
        ->toContain('namespace Modules\\Blog\\Filament\\Widgets;')
        ->toContain('extends StatsOverviewWidget');

    expectValidPhp($widget);
});

it('fails when the module does not exist', function () {
    artisan('filament-plugins:resource', [
        'model' => 'Post',
        'module' => 'Missing',
        '--panel' => 'admin',
        '--no-interaction' => true,
    ])->assertFailed();
});

it('generates a new plugin module', function () {
    artisan('filament-plugins:generate', [
        'name' => 'Shop',
        'description' => 'Shop plugin',
        'icon' => 'heroicon-o-shopping-cart',
        'color' => '#ff0000',
    ])
        ->expectsOutputToContain('Plugin generated successfully.')
        ->assertSuccessful();

    $module = TestCase::modulesPath().'/Shop';
    $info = json_decode(File::get($module.'/module.json'), true);

    expect($info)
        ->type->toBe('plugin')
        ->icon->toBe('heroicon-o-shopping-cart')
        ->and($info['title']['en'])->toBe('Shop')
        ->and($module.'/README.md')->toBeFile();

    $page = $module.'/app/Filament/Pages/ShopPage.php';

    expect(File::get($page))
        ->toContain('namespace Modules\\Shop\\Filament\\Pages;')
        ->toContain("protected string \$view = 'shop::index';")
        ->toContain('protected static string | BackedEnum | null $navigationIcon');

    expectValidPhp($page);
});
