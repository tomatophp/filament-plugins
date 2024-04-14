<?php

namespace TomatoPHP\TomatoPlugins\Services\Concerns\Flutter;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait GeneratePages
{
    private function generatePages(): void
    {
        $pagePath = base_path('/flutter/' . $this->appName . '/lib/app/modules/'.$this->module.'/views/'.$this->module.'Page.dart');
        $indexPagePath = base_path('/flutter/' . $this->appName . '/lib/app/modules/'.$this->module.'/views/'.$this->module.'PageIndex.dart');
        $createPagePath = base_path('/flutter/' . $this->appName . '/lib/app/modules/'.$this->module.'/views/'.$this->module.'PageCreate.dart');
        $editPagePath = base_path('/flutter/' . $this->appName . '/lib/app/modules/'.$this->module.'/views/'.$this->module.'PageEdit.dart');
        $viewPagePath = base_path('/flutter/' . $this->appName . '/lib/app/modules/'.$this->module.'/views/'.$this->module.'PageView.dart');


        //Delete Page If Exists
        if (File::exists($pagePath)) {
            File::delete($pagePath);
        }

        //Delete Index Page If Exists
        if (File::exists($indexPagePath)) {
            File::delete($indexPagePath);
        }

        //Delete Create Page If Exists
        if (File::exists($createPagePath)) {
            File::delete($createPagePath);
        }

        //Delete Edit Page If Exists
        if (File::exists($editPagePath)) {
            File::delete($editPagePath);
        }

        //Delete View Page If Exists
        if (File::exists($viewPagePath)) {
            File::delete($viewPagePath);
        }


        // Create Index Page
        $this->generateStubs(
            $this->stubPath . "/views/index.stub",
            $indexPagePath,
            [
                "title" => Str::of($this->table)->replace('_', ' ')->ucfirst()->toString(),
                "titleField" => $this->findTitleAndDescription()['title'],
                "descriptionField" => $this->findTitleAndDescription()['description'],
                "module" => $this->module,
                "table" => $this->table,
                "tableUpper" => $this->tableUpper,
                "moduleLower" => $this->moduleLower,
                "route" => Str::of($this->table)->replace('_', '-')->lower(),
            ]
        );

        // Create Create Page
        $this->generateStubs(
            $this->stubPath . "/views/create.stub",
            $createPagePath,
            [
                "title" => Str::of($this->table)->replace('_', ' ')->ucfirst()->toString(),
                "module" => $this->module,
                "table" => $this->table,
                "tableUpper" => $this->tableUpper,
                "moduleLower" => $this->moduleLower,
                "route" => Str::of($this->table)->replace('_', '-')->lower(),
                "fields" => $this->formFields(),
            ]
        );

        // Create Edit Page
        $this->generateStubs(
            $this->stubPath . "/views/edit.stub",
            $editPagePath,
            [
                "title" => Str::of($this->table)->replace('_', ' ')->ucfirst()->toString(),
                "module" => $this->module,
                "table" => $this->table,
                "tableUpper" => $this->tableUpper,
                "moduleLower" => $this->moduleLower,
                "route" => Str::of($this->table)->replace('_', '-')->lower(),
                "fields" => $this->formFields(),
            ]
        );

        // Create View Page
        $this->generateStubs(
            $this->stubPath . "/views/view.stub",
            $viewPagePath,
            [
                "title" => Str::of($this->table)->replace('_', ' ')->ucfirst()->toString(),
                "module" => $this->module,
                "table" => $this->table,
                "tableUpper" => $this->tableUpper,
                "moduleLower" => $this->moduleLower,
                "route" => Str::of($this->table)->replace('_', '-')->lower(),
                "fields" => $this->viewFields(),
            ]
        );

    }

    private function viewFields():string
    {
        $viewFields = "";
        foreach($this->cols as $key=>$item){
            $name = Str::of($item['name'])->replace('_', ' ')->ucfirst()->toString();
            if($key!== 0){
                $viewFields .= "                            ";
            }
            $viewFields .= "ListTile(";
            $viewFields .= PHP_EOL;
            $viewFields .= "                                title: Text('".$name."'),";
            $viewFields .= PHP_EOL;
            if($item['type'] === 'json' && ($item['name']== 'name' || $item['name']== 'title'|| $item['name']== 'description')){
                $viewFields .= "                                subtitle: Text(controller.data!.".$this->handelName($item['name'])."?.en.toString() ?? \"No data\"),";
            }
            else {
                $viewFields .= "                                subtitle: Text(controller.data!.".$this->handelName($item['name']).".toString() ?? \"No data\"),";
            }
            $viewFields .= PHP_EOL;
            $viewFields .= "                            ),";
            $viewFields .= PHP_EOL;


            if($key!== count($this->cols)-1){
                $viewFields .= PHP_EOL;
            }
        }
        return $viewFields;
    }

    private function formFields():string
    {
         $formFields = "";
        foreach($this->cols as $key=>$item){
            $name = Str::of($item['name'])->replace('_', ' ')->ucfirst()->toString();
            if($key!== 0){
                $formFields .= "                                      ";
            }
           if($item['name'] !== 'id'){
               if($item['type'] === 'email'){
                   $formFields .= "FormInput.email(";
               }
               else if($item['type'] === 'password'){
                   $formFields .= "FormInput.password(";
               }
               else if($item['type'] === 'int' || $item['type'] === 'double' || $item['type'] === 'flot' || $item['type'] === 'integer'){
                   $formFields .= "FormInput.number(";
               }
               else {
                   $formFields .= "FormInput.text(";
               }
               $formFields .= PHP_EOL;
               $formFields .= "                                        controller: controller.".$item['name']."Input,";
               $formFields .= PHP_EOL;
               $formFields .= "                                        placeholder: \"".$name."\",";
               $formFields .= PHP_EOL;
               $formFields .= "                                        leading: Icon(Icons.text_fields),";
               $formFields .= PHP_EOL;
               $formFields .= "                                        validator: (value) =>";
               $formFields .= PHP_EOL;
               $formFields .= "                                            Validator(\"".$name."\", value!)";
               if($item['type'] === 'email'){
                   $formFields .= ".email()";
               }
               if($item['required'] === 'required'){
                   $formFields .= ".required()";
               }
               if($item['maxLength']){
                   $formFields .= ".max(".$item['maxLength'].")";
               }
               $formFields .= ".validate(),";
               $formFields .= PHP_EOL;
               $formFields .= "                                      ),";
               $formFields .= PHP_EOL;
               $formFields .= "                                      SizedBox(height: 25),";
           }

            if($key!== count($this->cols)-1){
                $formFields .= PHP_EOL;
            }
        }
        return $formFields;
    }


    private function findTitleAndDescription(): array
    {
        $title = "";
        $description = "";
        foreach($this->cols as $key=>$item){
            if( $item['name'] === 'first_name' || $item['name'] === 'label' || $item['name'] === 'title' || $item['name'] === 'name'){
                $title = $item['name'];
            }
            else if( $item['name'] === 'desc' || $item['name'] === 'details' || $item['name'] === 'detail' || $item['name'] === 'about' || $item['name'] === 'body' ||  $item['name'] === 'address' || $item['name'] === 'description' || $item['name'] === 'email' || $item['name'] === 'phone'){
                $description = $item['name'];
            }
        }

        if(count($this->cols) > 2){
            if($title === ""){
                $title = $this->cols[1]['name'];
            }
            if($description === ""){
                $description = $this->cols[2]['name'];
            }
        }
        else {
            if($title === ""){
                $title = $this->cols[1]['name'];
            }
            if($description === ""){
                $description = $this->cols[1]['name'];
            }
        }


        return [
            "title" => $title,
            "description" => $description
        ];
    }

}
