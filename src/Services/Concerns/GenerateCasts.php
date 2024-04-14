<?php

namespace TomatoPHP\TomatoPlugins\Services\Concerns;

trait GenerateCasts
{
    private function generateCasts()
    {
        $this->injectString(
            $this->moduleName ? module_path($this->moduleName) ."/App/Models/{$this->modelName}.php" : app_path("Models/{$this->modelName}.php"),
            'protected $fillable =',
            $this->stubPath . "casts.stub",
            [
                "casts" => $this->getCasts(),
            ]
        );
    }

    private function getCasts()
    {
        $casts = [];
        foreach ($this->cols as $key=>$column) {
            if ($column['type'] == 'boolean') {
                $casts[] = ($key!==0?'        ':"") .'\''.$column['name'].'\' => \'boolean\'';
            }
            elseif ($column['type'] == 'json') {
                $casts[] = ($key!==0?'        ':"") .'\''.$column['name'].'\' => \'json\'';
            }
        }
        return implode(",\n", $casts);
    }
}
