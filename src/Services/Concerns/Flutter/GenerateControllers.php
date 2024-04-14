<?php

namespace TomatoPHP\TomatoPlugins\Services\Concerns\Flutter;

use Illuminate\Support\Facades\File;

trait GenerateControllers
{
    private function generateControllers(): void
    {
        $controllerPath = base_path('/flutter/' . $this->appName . '/lib/app/modules/'.$this->module.'/controllers/'.$this->module.'Controller.dart');
        $indexControllerPath = base_path('/flutter/' . $this->appName . '/lib/app/modules/'.$this->module.'/controllers/'.$this->module.'IndexController.dart');
        $createControllerPath = base_path('/flutter/' . $this->appName . '/lib/app/modules/'.$this->module.'/controllers/'.$this->module.'CreateController.dart');
        $editControllerPath = base_path('/flutter/' . $this->appName . '/lib/app/modules/'.$this->module.'/controllers/'.$this->module.'EditController.dart');
        $viewControllerPath = base_path('/flutter/' . $this->appName . '/lib/app/modules/'.$this->module.'/controllers/'.$this->module.'ViewController.dart');

        //Delete Controller If Exists
        if (File::exists($controllerPath)) {
            File::delete($controllerPath);
        }


        //Delete Index Controller If Exists
        if (File::exists($indexControllerPath)) {
            File::delete($indexControllerPath);
        }

        //Delete Create Controller If Exists
        if (File::exists($createControllerPath)) {
            File::delete($createControllerPath);
        }

        //Delete Edit Controller If Exists
        if (File::exists($editControllerPath)) {
            File::delete($editControllerPath);
        }

        //Delete View Controller If Exists
        if (File::exists($viewControllerPath)) {
            File::delete($viewControllerPath);
        }

        //Create Index Controller
        $this->generateStubs(
            $this->stubPath . "/controllers/index.stub",
            $indexControllerPath,
            [
                "module" => $this->module,
                "table" => $this->table,
                "tableUpper" => $this->tableUpper,
                "moduleLower" => $this->moduleLower
            ]
        );

        // Create Create Controller
        $this->generateStubs(
            $this->stubPath . "/controllers/create.stub",
            $createControllerPath,
            [
                "module" => $this->module,
                "table" => $this->table,
                "tableUpper" => $this->tableUpper,
                "moduleLower" => $this->moduleLower,
                "fields" => $this->controllerFields(),
                "jsonFields" => $this->controllerJsonFields()
            ]
        );

        // Create Edit Controller
        $this->generateStubs(
            $this->stubPath . "/controllers/edit.stub",
            $editControllerPath,
            [
                "module" => $this->module,
                "table" => $this->table,
                "tableUpper" => $this->tableUpper,
                "moduleLower" => $this->moduleLower,
                "fields" => $this->controllerFields(),
                "jsonFields" => $this->controllerJsonFields(),
                "fieldsWithData" => $this->fieldsWithData()
            ]
        );

        // Create View Controller
        $this->generateStubs(
            $this->stubPath . "/controllers/view.stub",
            $viewControllerPath,
            [
                "module" => $this->module,
                "table" => $this->table,
                "tableUpper" => $this->tableUpper,
                "moduleLower" => $this->moduleLower
            ]
        );
    }

    private function fieldsWithData():string
    {
        $fieldsWithData = "";
        foreach($this->cols as $key=>$item){
            if($key!== 0){
                $fieldsWithData .= "  ";
                if($item['type'] === 'json' && ($item['name']== 'name' ||$item['name']== 'title'|| $item['name']== 'description')){
                    $fieldsWithData .= $item['name']."Input.text = data.data!.".$this->handelName($item['name'])."!.en!.toString();";
                }
                else if($item['name'] !== 'id') {
                    $fieldsWithData .= $item['name']."Input.text = data.data!.".$this->handelName($item['name'])."!.toString();";
                }
            }

            if($key!== count($this->cols)-1){
                $fieldsWithData .= PHP_EOL;
            }
        }
        return $fieldsWithData;
    }

    private function controllerFields(): string
    {
        $controllerFields = "";
        foreach($this->cols as $key=>$item){
            if($key!== 0){
                $controllerFields .= "  ";
            }
            if($item['name'] !== 'id') {
                $controllerFields .= "final TextEditingController " . $item['name'] . "Input = TextEditingController();";
            }

            if($key!== count($this->cols)-1){
                $controllerFields .= PHP_EOL;
            }
        }
        return $controllerFields;
    }

    private function controllerJsonFields(): string
    {
        $controllerJsonFields = "";
        foreach($this->cols as $key=>$item){
            if($key!== 0){
                $controllerJsonFields .= "        ";
            }
            if($item['name'] !== 'id'){
                $controllerJsonFields .= "\"".$item['name']."\": ".$item['name']."Input.text,";
            }


            if($key!== count($this->cols)-1){
                $controllerJsonFields .= PHP_EOL;
            }
        }
        return $controllerJsonFields;
    }
}
