<?php

namespace TomatoPHP\TomatoPlugins\Services\Concerns;

use Illuminate\Support\Str;
use ProtoneMedia\Splade\FormBuilder\Text;

trait GenerateFormView
{
    private function generateFormView(): void
    {
        $folders = [];
        if ($this->moduleName) {
            $folders[] = module_path($this->moduleName) . "/resources/views/" . Str::replace('_', '-',$this->tableName);
        } else {
            $folders[] = resource_path("views/admin");
            $folders[] = resource_path("views/admin/" . Str::replace('_', '-',$this->tableName));
        }

        $this->generateStubs(
            $this->stubPath ."FormBuilder/Form.stub",
            $this->moduleName ? module_path($this->moduleName) . "/resources/views/" . str_replace('_', '-', $this->tableName) . "/form.blade.php" : resource_path("views/admin/".Str::replace('_', '-',$this->tableName)."/form.blade.php"),
            [
                "title" => $this->modelName,
                "table" => str_replace('_', '-', $this->tableName),
                "cols" => $this->generateForm()
            ],
            $folders
        );
    }

    private function generateFormBuilderClass()
    {
        $this->generateStubs(
            $this->stubPath ."FormBuilder/FormClass.stub",
            $this->moduleName ? module_path($this->moduleName) . "/App/Forms/{$this->modelName}Form.php" : app_path("Forms/{$this->modelName}Form.php"),
            [
                "name" => "{$this->modelName}Form",
                "route" => str_replace('_', '-', $this->tableName),
                "cols" => $this->generateFormElements(),
                "namespace" => $this->moduleName ? "Modules\\" . $this->moduleName . "\\App\\Forms" : "App\\Forms",
            ],
            [
                $this->moduleName ? module_path($this->moduleName) . "/App/Forms" : app_path("Forms")
            ]
        );
    }

    private function generateFormElements(): string
    {
        //     $item['type'] === 'tel' || color
        $types = ["string" => "Text", "email" => "Email", "tel" => "Text", "password" => "Password", "textarea" => "Textarea", "int" => "Number", "date" => "Date", "datetime" => "Datetime", "time" => "Time"];
        $form = "";
        foreach ($this->cols as $key => $item) {

            if (array_key_exists($item['type'],$types)){
                $form .= "              \ProtoneMedia\Splade\FormBuilder\\".$types[$item['type']]."::make('" . $item['name'] . "')->label(__('" . Str::ucfirst(str_replace('_', ' ', $item['name'])) . "')),";
                $form .= PHP_EOL;
            }

            if ($item['type']== 'boolean'){
                $form .= "              \ProtoneMedia\Splade\FormBuilder\Checkbox::make('" . $item['name'] . "')->label(__('" . Str::ucfirst(str_replace('_', ' ', $item['name'])) . "'))->value(1),";
                $form .= PHP_EOL;
            }

         if ($item['type'] === 'relation') {

            $itemLable = ($item['relation']['relationColumnType'] == 'json') ? 'name.' . app()->getLocale() : 'name';
            $form .= "              \ProtoneMedia\Splade\FormBuilder\Select::make('".$item['name']."')
                ->label(__('".Str::remove('_id',$item['name'])."'))
                ->choices()
                ->remoteUrl('/admin/".$item['relation']['table']."/api')
                ->remoteRoute('model.data')
                ->optionLabel('".$itemLable."')
                ->optionValue('id'),";
             $form .= PHP_EOL;
        }

        if ($item['type'] === 'json' && ($item['name'] == 'name' || $item['name'] == 'title' || $item['name'] == 'description')) {

                $form .= "              \ProtoneMedia\Splade\FormBuilder\Text::make('" . $item['name'] . ".ar')->label(__('" . Str::ucfirst(str_replace('_', ' ', $item['name'])) . "_ar')),";
                $form .= PHP_EOL;
                $form .= "              \ProtoneMedia\Splade\FormBuilder\Text::make('" . $item['name'] . ".en')->label(__('" . Str::ucfirst(str_replace('_', ' ', $item['name'])) . "_en')),";
            $form .= PHP_EOL;
        }


        if ($key !== count($this->cols) - 1) {
            $form .= PHP_EOL;
        }
    }
return $form;
}

}
