<?php

namespace TomatoPHP\FilamentPlugins\Services\Concerns;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use TomatoPHP\FilamentPlugins\Models\Table;
use TomatoPHP\FilamentPlugins\Models\TableCol;

trait GenerateCols
{
    private function get_string_between( $string, $start, $end ){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    private function getCols(): array
    {
        $components = [];

        $columns = collect(Schema::getColumns($this->tableName));
        $keys = collect(Schema::getForeignKeys($this->tableName));
        $indexs = collect(Schema::getIndexes($this->tableName));

        $types=[];

        foreach ($columns as $column) {
            if (Str::of($column['name'])->endsWith([
                'created_at',
                'updated_at',
                'deleted_at',
                '_token',
            ])) {
                continue;
            }

            $componentData = [];

            $componentData['name'] = $column['name'];
            $componentData['type']=$column['type_name'] === 'varchar' ? 'string' : $column['type_name'];
            $componentData['default']=$column['default'];
            $componentData['unique'] = $indexs->where('name', $this->tableName . '_' . $column['name'] . '_unique')->first() ? true : false;


            if ($componentData['type'] === "string") {
                if (Str::of($column['name'])->contains(['email'])) {
                    $componentData['type'] = "email";
                }

                if (Str::of($column['name'])->contains(['password'])) {
                    $componentData['type'] = "password";
                }

                if (Str::of($column['name'])->contains(['phone', 'tel'])) {
                    $componentData['type'] = "tel";
                }

                if (Str::of($column['name'])->contains(['color'])) {
                    $componentData['type'] = "color";
                }

                if (Str::of($column['name'])->contains(['icon'])) {
                    $componentData['type'] = "icon";
                }
            }
            if ($componentData['type'] === "integer" || $componentData['type'] === "float" || $componentData['type'] === "double") {
                $componentData['type'] = "int";
            }
            if ($componentData['type'] === "tinyint") {
                $componentData['type'] = "boolean";
            }

            $hasForgenKey = $keys->where('name',$this->tableName . '_' . $column['name'] . '_foreign')->first();
            if ($hasForgenKey) {
                $getKey = $hasForgenKey;
                $model = "\\Modules\\" . $this->moduleName . "\\App\\Models\\" . Str::studly(Str::singular($getKey['foreign_table']));
                $componentData['relation'] = [
                    "table" => $getKey['foreign_table'],
                    "field" => $getKey['foreign_columns'][0],
                    "model" => $model,
                    'relationColumn'=>$getKey['foreign_columns'][0],
                    'relationColumnType'=>'text'
                ];

                $relationTableColumns=Schema::getColumnListing($componentData['relation']['table']);
                if (array_search('name',$relationTableColumns))
                    $componentData['relation']['relationColumn']='name';
                elseif (array_search('title',$relationTableColumns))
                    $componentData['relation']['relationColumn']='title';

                try {
                    $componentData['relation']['relationColumnType']=Schema::getColumnType($componentData['relation']['table'],$componentData['relation']['relationColumn']);
                }catch (\Exception $e) {}

                $componentData['type'] = 'relation';
            }

            if ($column['nullable']) {
                $componentData['required'] = 'required';
            } else {
                $componentData['required'] = 'nullable';
            }

            $length = (int)$this->get_string_between($column['type'], '(', ')');
            if ($length) {
                if ($length > 255) {
                    $componentData['type'] = 'textarea';
                }
                $componentData['maxLength'] = $length;
            } else {
                $componentData['maxLength'] = false;
            }

            if($length < 1 && $componentData['type'] === 'text'){
                $componentData['type'] = 'longText';
            }

            $components[] = $componentData;
        }

        $checkifTableExists = Table::where('name', $this->tableName)->first();
        if(!$checkifTableExists){
            $table = new Table();
            $table->module = $this->moduleName;
            $table->name = $this->tableName;
            $table->timestamps = $columns->where('name', 'created_at')->count() > 0;
            $table->soft_deletes = $columns->where('name', 'deleted_at')->count() > 0;
            $table->migrated = true;
            $table->save();

            foreach ($components as $component){
                $tableCol = new TableCol();
                $tableCol->table_id = $table->id;
                $tableCol->name = $component['name'];
                $tableCol->type = $component['type'];
                $tableCol->length = $component['maxLength'];
                $tableCol->unique = $component['unique'];
                $tableCol->default = $component['default'];
                if($component['type'] === 'relation'){
                    $tableCol->foreign = true;
                    $tableCol->foreign_table = $component['relation']['table'];
                    $tableCol->foreign_col = $component['relation']['field'];
                    $tableCol->foreign_model = $component['relation']['model'];
                    $tableCol->foreign_on_delete_cascade = false;
                }
                $tableCol->nullable = !$component['required'];
                $tableCol->save();
            }

        }
        else {
            $this->table = $checkifTableExists;
        }

        return $components;
    }
}
