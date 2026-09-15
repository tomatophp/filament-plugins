<?php

namespace TomatoPHP\FilamentPlugins\Services\Concerns;

use Illuminate\Support\Str;
use TomatoPHP\FilamentPlugins\Models\TableCol;
use TomatoPHP\FilamentPlugins\Services\Concerns\Kirschbaum\PowerJoins\PowerJoins;

trait GenerateTable
{
    private function generateTable(): void
    {
        $this->generateStubs(
            $this->stubPath.'table.stub',
            $this->moduleName ? module_path($this->moduleName)."/app/Tables/{$this->modelName}Table.php" : app_path("Tables/{$this->modelName}Table.php"),
            [
                'name' => "{$this->modelName}Table",
                'title' => $this->modelName,
                'model' => $this->moduleName ? '\\Modules\\'.$this->moduleName.'\\Models\\'.$this->modelName : '\\App\\Models\\'.$this->modelName,
                'searchable' => $this->generateSearchable(),
                'cols' => $this->generateCols(),
                'namespace' => $this->moduleName ? 'Modules\\'.$this->moduleName.'\\Tables' : 'App\\Tables',
            ],
            [
                $this->moduleName ? module_path($this->moduleName).'/app/Tables' : app_path('Tables'),
            ]
        );
    }

    private function generateSearchable(): string
    {
        $searchable = '';
        foreach ($this->table->tableCols as $key => $item) {
            if ($item['unique']) {
                $searchable .= "'{$item->name}',";
            } elseif ($item->name === 'id') {
                $searchable .= "'{$item->name}',";
            } elseif ($item->name === 'name') {
                $searchable .= "'{$item->name}',";
            } elseif ($item->name === 'phone') {
                $searchable .= "'{$item->name}',";
            } elseif ($item->name === 'email') {
                $searchable .= "'{$item->name}',";
            } elseif ($item->foreign && class_exists(PowerJoins::class)) {
                $searchable .= "'".Str::remove('_id', $item->name).'.'.$item->foreign_col."',";
            }
        }

        return $searchable;
    }

    private function generateCols(): string
    {
        $cols = '';
        foreach ($this->table->tableCols as $key => $item) {
            if ($item->name !== 'password') {
                if ($key !== 0) {
                    $cols .= '            ';
                }
                $cols .= $this->checkColumnForRelation($item);
                if ($key !== $this->table->tableCols()->count() - 1) {
                    $cols .= PHP_EOL;
                }
            }
        }

        return $cols;
    }

    private function checkColumnForRelation(TableCol $item)
    {
        $column = "->column(
                key: '".$item->name."',
                label: __('".Str::of($item->name)->replace('_', ' ')->ucfirst()."'),
                sortable: true
            )";
        if ($item->foreign && class_exists(PowerJoins::class)) {
            $column = "->column(
                key: '".Str::remove('_id', $item->name).'.'.$item->foreign_col."',
                label: __('".Str::of($item->name)->remove('_id')->replace('_', ' ')->ucfirst()."'),
                sortable: true,
                searchable: true
            )";
        }

        return $column;
    }
}
