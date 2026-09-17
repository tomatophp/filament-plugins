<?php

namespace TomatoPHP\FilamentPlugins\Services;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use TomatoPHP\ConsoleHelpers\Traits\HandleStub;
use TomatoPHP\FilamentPlugins\Models\Table;
use TomatoPHP\FilamentPlugins\Services\Concerns\GenerateCols;
use TomatoPHP\FilamentPlugins\Services\Concerns\GenerateController;
use TomatoPHP\FilamentPlugins\Services\Concerns\GenerateCreateView;
use TomatoPHP\FilamentPlugins\Services\Concerns\GenerateEditView;
use TomatoPHP\FilamentPlugins\Services\Concerns\GenerateFolders;
use TomatoPHP\FilamentPlugins\Services\Concerns\GenerateForm;
use TomatoPHP\FilamentPlugins\Services\Concerns\GenerateFormView;
use TomatoPHP\FilamentPlugins\Services\Concerns\GenerateIndexView;
use TomatoPHP\FilamentPlugins\Services\Concerns\GenerateJsonResource;
use TomatoPHP\FilamentPlugins\Services\Concerns\GenerateMenus;
use TomatoPHP\FilamentPlugins\Services\Concerns\GenerateMigrations;
use TomatoPHP\FilamentPlugins\Services\Concerns\GenerateModel;
use TomatoPHP\FilamentPlugins\Services\Concerns\GenerateRequest;
use TomatoPHP\FilamentPlugins\Services\Concerns\GenerateRoutes;
use TomatoPHP\FilamentPlugins\Services\Concerns\GenerateRules;
use TomatoPHP\FilamentPlugins\Services\Concerns\GenerateShowView;
use TomatoPHP\FilamentPlugins\Services\Concerns\GenerateTable;
use TomatoPHP\FilamentPlugins\Services\Concerns\InjectString;

class CRUDGenerator
{
    private string $modelName;

    private string $stubPath;

    private array $cols = [];

    use GenerateCols;
    use GenerateController;
    use GenerateCreateView;
    use GenerateEditView;

    // Generate Classes
    use GenerateFolders;

    // Generate From & View
    use GenerateForm;
    use GenerateFormView;

    // Generate Views
    use GenerateIndexView;
    use GenerateJsonResource;
    use GenerateMenus;
    use GenerateMigrations;
    use GenerateModel;
    use GenerateRequest;
    use GenerateRoutes;
    use GenerateRules;
    use GenerateShowView;
    use GenerateTable;

    // Handler
    use HandleStub;
    use InjectString;

    /**
     * @throws Exception
     */
    public function __construct(
        private ?Table $table = null,
        private ?string $tableName = null,
        private string|bool|null $moduleName = null,
        private bool $isBuilder = false,
        private array $fields = [],
        private bool $module = false,
        private bool $migration = true,
        private bool $controllers = false,
        private bool $request = false,
        private bool $models = false,
        private bool $views = false,
        private bool $tables = false,
        private bool $routes = false,
        private bool $apiRoutes = false,
        private bool $json = false,
        private bool $menu = false,
    ) {
        if (! $this->tableName) {
            $this->tableName = $this->table->name;

        }
        if (! $this->moduleName) {
            $this->moduleName = $this->table->module;
        }
        $this->modelName = Str::ucfirst(Str::singular(Str::camel($this->tableName)));
        $this->stubPath = __DIR__.'/../../stubs/';
        $this->cols = $this->getCols();
    }

    /**
     * @return void
     */
    public function generate(): bool
    {
        if ($this->migration) {
            $this->generateMigrations();
        }
        if (Schema::hasTable($this->tableName)) {
            $this->generateFolders();
            if ($this->models) {
                $this->generateModel();
            }
            if ($this->tables) {
                $this->generateTable();
            }
            if ($this->isBuilder) {
                $this->generateControllerForBuilder();
            } elseif ($this->request) {
                $this->generateRequest();
                if ($this->controllers) {
                    $this->generateControllerForRequest();
                }
            } elseif ($this->controllers && (! $this->request) && (! $this->isBuilder)) {
                $this->generateController();
            }

            if ($this->json) {
                $this->generateJsonResource();
            }
            if ($this->routes || $this->apiRoutes) {
                $this->generateRoutes();
            }
            if ($this->views) {
                $this->generateIndexView();
                if ($this->isBuilder) {
                    $this->generateFormView();
                    $this->generateFormBuilderClass();

                } else {
                    $this->generateCreateView();
                    $this->generateEditView();
                }
                $this->generateShowView();
            }
            if ($this->menu) {
                $this->generateMenus();
            }

            return true;
        } else {
            return false;
        }

    }
}
