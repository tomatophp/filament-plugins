<?php

namespace TomatoPHP\TomatoPlugins\Services\Concerns;

trait GenerateRules
{
    private function generateRules(bool $edit = false): string
    {
        $rules = "";
        foreach ($this->table->tableCols as $key => $item) {
            if ($item->name !== 'id') {
                if($key !== 0){
                    $rules .= "            ";
                }
                $rules .= "'{$item->name}' => ";
                $rules .= "'";
                if(!$item->nullable){
                    if($edit){
                        $rules .= 'sometimes';
                    }
                    else {
                        $rules .= 'required';
                    }

                }
                else {
                    $rules .= 'nullable';
                }

                if($item->length){
                    $rules .= '|max:'.$item->length;
                }
                if($item->type === 'string'){
                    $rules .= '|string';
                }
                if($item->name === 'email'){
                    $rules .= '|email';
                }
                if($item->name === 'phone'){
                    $rules .= '|min:12';
                }
                if($item->name === 'password'){
                    $rules .= '|confirmed|min:6';
                }
                if($item->foreign){
                    $rules .= '|exists:'.$item->foreign_table.',id';
                }

                $rules .= "'";
                if (($key !== $this->table->tableCols()->count() - 1)) {
                    $rules .= ",".PHP_EOL;
                }
            }
        }

        return $rules;
    }
}
