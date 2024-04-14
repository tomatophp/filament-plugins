<?php

namespace TomatoPHP\TomatoPlugins\Services\Concerns;

use Illuminate\Support\Str;
use TomatoPHP\TomatoPlugins\Models\TableCol;

trait GenerateTable
{
    private function generateTable(): void
    {
        $this->generateStubs(
            $this->stubPath . "table.stub",
            $this->moduleName ? module_path($this->moduleName). "/App/Tables/{$this->modelName}Table.php" : app_path("Tables/{$this->modelName}Table.php"),
            [
                "name" => "{$this->modelName}Table",
                "title" => $this->modelName,
                "model" => $this->moduleName ? "\\Modules\\".$this->moduleName."\\App\\Models\\".$this->modelName :"\\App\\Models\\".$this->modelName,
                "searchable" => $this->generateSearchable(),
                "cols" => $this->generateCols(),
                "namespace" => $this->moduleName ? "Modules\\".$this->moduleName."\\App\\Tables" : "App\\Tables",
            ],
            [
                $this->moduleName ? module_path($this->moduleName)."/App/Tables" : app_path("Tables")
            ]
        );
    }

    private function generateSearchable(): string
    {
        $searchable = "";
        foreach($this->table->tableCols as $key=>$item){
            if($item['unique']){
                $searchable .= "'{$item->name}',";
            }
            else if($item->name === 'id'){
                $searchable .= "'{$item->name}',";
            }
            else if($item->name === 'name'){
                $searchable .= "'{$item->name}',";
            }
            else if($item->name === 'phone'){
                $searchable .= "'{$item->name}',";
            }
            else if($item->name === 'email'){
                $searchable .= "'{$item->name}',";
            }
            else if($item->foreign && class_exists(Kirschbaum\PowerJoins\PowerJoins::class)){
                $searchable .= "'".Str::remove('_id', $item->name).".".$item->foreign_col."',";
            }
        }

        return $searchable;
    }

    private function generateCols(): string
    {
        $cols = "";
        foreach($this->table->tableCols as $key=>$item){
            if($item->name !== 'password'){
                if($key!== 0){
                    $cols .= "            ";
                }
                $cols .= $this->checkColumnForRelation($item);
                if($key!== $this->table->tableCols()->count()-1){
                    $cols .= PHP_EOL;
                }
            }
        }
        return $cols;
    }
    private function checkColumnForRelation(TableCol $item){
        $column="->column(
                key: '".$item->name."',
                label: __('".Str::of($item->name)->replace('_',' ')->ucfirst()."'),
                sortable: true
            )";
            if ($item->foreign && class_exists(Kirschbaum\PowerJoins\PowerJoins::class)){
                $column= "->column(
                key: '".Str::remove('_id', $item->name).".".$item->foreign_col."',
                label: __('".Str::of($item->name)->remove('_id')->replace('_',' ')->ucfirst()."'),
                sortable: true,
                searchable: true
            )";
            }
        return $column;
    }
}
