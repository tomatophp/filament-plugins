<?php

namespace TomatoPHP\TomatoPlugins\Services\Concerns;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait GenerateMigrations
{
    /**
     * @return array
     */
    private function generateMigrations(): void
    {
        $migrationsPath = module_path($this->moduleName) ."/Database/migrations/";
        if(!File::exists($migrationsPath)){
            File::makeDirectory($migrationsPath);
        }
        $checkIfMigrationExists = File::files($migrationsPath);
        $migrationExists = false;
        foreach ($checkIfMigrationExists as $migration){
            if(Str::of($migration->getFilename())->contains('_create_' . $this->tableName . '_table')){
                File::delete($migration);
            }
        }

        Schema::dropIfExists($this->tableName);

        $this->generateStubs(
            $this->stubPath . 'migration.stub',
            module_path($this->moduleName) ."/Database/migrations/". date('Y_m_d_h_mm_ss') . '_create_' . $this->tableName . '_table.php',
            [
                "table" => $this->tableName,
                "fields" => $this->getFields($this->table->tableCols)
            ],
        );
    }

    /**
     * @param $fields
     * @return string
     */
    private function getFields($fields): string
    {
        $finalFields = "";
        foreach ($fields as $key=>$field){
            $empty = false;
            if($key !== 0){
                $finalFields .= "            ";
            }
            if($field->name === 'id'){
                $finalFields .= '$table->id()';
            }
            else if($field->type === 'bigint'){
                if($field->foreign){
                    $finalFields .= '$table->foreignId("'.$field->name.'")';
                }
                else {
                    $finalFields .= '$table->bigInteger("'.$field->name.'")';
                }
            }
            else {
                $finalFields .= '$table->'. ($field->type == 'varchar' ? 'string' : $field->type) .'("'.$field->name.'")';
            }


            if(isset($field->default) && $field->default){
                if($field->type === 'boolean' || $field->type === 'float' || $field->type === 'int' || $field->type === 'double'){
                    $finalFields .= "->default(".$field->default.")";
                }
                else {
                    $finalFields .= "->default('".$field->default."')";
                }
            }

            if(isset($field->nullable) && $field->nullable){
                $finalFields .= "->nullable()";
            }

            if(isset($field->foreign) && $field->foreign){
                $finalFields .= "->references('".$field->foreign_col."')->on('".$field->foreign_table."')";
                if($field->foreign_on_delete_cascade){
                    $finalFields .= "->onDelete('cascade')";
                }
            }
            if(isset($field->unique) && $field->unique){
                $finalFields .= "->unique()";
            }

            if(isset($field->unsigned) && $field->unsigned && $field->name !== 'id'){
                $finalFields .= "->unsigned()";
            }

            if(isset($field->comment)){
                $finalFields .= "->comment('".$field->comment."')";
            }

            if(isset($field->primary) && $field->primary && $field->name !== 'id'){
                $finalFields .= "->primary()";
            }

            if(isset($field->index) && $field->index){
                $finalFields .= "->index()";
            }

            if(!$empty){
                $finalFields .= ";";
                $finalFields .= "\n";
            }

        }

        if($this->table->timestamps){
            if(!Str::of($finalFields)->contains('$table->timestamps()')){
                $finalFields .= '            $table->timestamps();' . "\n";
            }
        }
        if($this->table->soft_deletes){
            $finalFields .= '            $table->softDeletes();' . "\n";
        }

        return $finalFields;
    }
}
