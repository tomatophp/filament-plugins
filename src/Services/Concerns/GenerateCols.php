<?php

namespace TomatoPHP\TomatoPlugins\Services\Concerns;

use Illuminate\Support\Str;

trait GenerateCols
{
    private function getCols(): array
    {
        $components = [];

        $this->connection->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        $tableSchema = $this->connection->getSchemaManager();
        $columns = $tableSchema->listTableDetails($this->tableName);

        $types=[];

        foreach ($columns->getColumns() as $column) {

            if (Str::of($column->getName())->endsWith([
                'created_at',
                'updated_at',
                'deleted_at',
                '_token',
            ])) {
                continue;
            }

            $componentData = [];

            $componentData['name'] = $column->getName();
            $componentData['type']=$column->getType()->getName();
            $componentData['default']=$column->getDefault();


            $uniqueName = $this->tableName . '_' . $column->getName() . '_unique';
            if ($columns->hasIndex($uniqueName)) {
                $componentData['unique'] = true;
            } else {
                $componentData['unique'] = false;
            }

            if ($componentData['type'] === "string") {

                if (Str::of($column->getName())->contains(['email'])) {
                    $componentData['type'] = "email";
                }

                if (Str::of($column->getName())->contains(['password'])) {
                    $componentData['type'] = "password";
                }

                if (Str::of($column->getName())->contains(['phone', 'tel'])) {
                    $componentData['type'] = "tel";
                }

                if (Str::of($column->getName())->contains(['color'])) {
                    $componentData['type'] = "color";
                }

                if (Str::of($column->getName())->contains(['icon'])) {
                    $componentData['type'] = "icon";
                }
            }
            if ($componentData['type'] === "integer" || $componentData['type'] === "float" || $componentData['type'] === "double") {
                $componentData['type'] = "int";
            }

            if (Str::of($column->getName())->endsWith([
                '_id'
            ]))
            {

                if ($columns->hasForeignKey($this->tableName . '_' . $column->getName() . '_foreign')) {
                    $getKey = $columns->getForeignKey($this->tableName . '_' . $column->getName() . '_foreign');
                    $model = "\\Modules\\" . $this->moduleName . "\\Entities\\" . Str::studly(Str::singular($getKey->getForeignTableName()));
                    $componentData['relation'] = [
                        "table" => $getKey->getForeignTableName(),
                        "field" => $getKey->getForeignColumns()[0],
                        "model" => $model,
                        'relationColumn'=>'id',
                        'relationColumnType'=>'text'
                    ];

                    $relationTableColumns=\Illuminate\Support\Facades\Schema::getColumnListing($componentData['relation']['table']);
                    if (array_search('name',$relationTableColumns))
                        $componentData['relation']['relationColumn']='name';
                    elseif (array_search('title',$relationTableColumns))
                        $componentData['relation']['relationColumn']='title';

                    try {
                        $componentData['relation']['relationColumnType']=\Illuminate\Support\Facades\Schema::getColumnType($componentData['relation']['table'],$componentData['relation']['relationColumn']);
                    }catch (\Exception $e) {}

                    $componentData['type'] = 'relation';
                }
            }

            if ($column->getNotnull()) {
                $componentData['required'] = 'required';
            } else {
                $componentData['required'] = 'nullable';
            }


            if ($length = $column->getLength()) {
                if ($length > 255) {
                    $componentData['type'] = 'textarea';
                }
                $componentData['maxLength'] = $length;
            } else {
                $componentData['maxLength'] = false;
            }

            if($column->getLength() < 1 && $componentData['type'] === 'text'){
                $componentData['type'] = 'longText';
            }

            $components[] = $componentData;
        }

//        dd($components);
        return $components;
    }
}
