<?php

namespace TomatoPHP\TomatoPlugins\Services\Concerns\Flutter;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait GenerateModel
{
    private bool $hasJson = false;

    public function generateModel(): void
    {
        $singleModelPath = base_path('/flutter/' . $this->appName . '/lib/app/models/'.$this->tableUpper.'SingleModel.dart');
        $tableModelPath = base_path('/flutter/' . $this->appName . '/lib/app/models/'.$this->tableUpper.'Model.dart');

        //Delete Single If Exists
        if(File::exists($singleModelPath)){
            File::delete($singleModelPath);
        }

        //Delete Table If Exists
        if(File::exists($tableModelPath)){
            File::delete($tableModelPath);
        }

        //Create Single Model
        $this->generateStubs(
            $this->stubPath . "/models/single.stub",
            $singleModelPath,
            [
                "tableUpper" => $this->tableUpper
            ]
        );

        //Create Table Model
        $this->generateStubs(
            $this->stubPath . "/models/model.stub",
            $tableModelPath,
            [
                "tableUpper" => $this->tableUpper,
                "tableFields" => $this->tableFields(),
                "tableThis" => $this->tableThis(),
                "tableJson" => $this->tableJson(),
                "tableData" => $this->tableData(),
                "modelMore" => $this->modelMore(),
            ]
        );
    }

    private function modelMore():string
    {
        if($this->hasJson){

        }
       $modelMore = "";
        $modelMore .= "class Name {";
        $modelMore .= PHP_EOL;
        $modelMore .= "  String? ar;";
        $modelMore .= PHP_EOL;
        $modelMore .= "  String? en;";
        $modelMore .= PHP_EOL;
        $modelMore .= PHP_EOL;
        $modelMore .= "  Name({this.ar, this.en});";
        $modelMore .= PHP_EOL;
        $modelMore .= PHP_EOL;
        $modelMore .= "  Name.fromJson(Map<String, dynamic> json) {";
        $modelMore .= PHP_EOL;
        $modelMore .= "    ar = json['ar'];";
        $modelMore .= PHP_EOL;
        $modelMore .= "    en = json['en'];";
        $modelMore .= PHP_EOL;
        $modelMore .= "  }";
        $modelMore .= PHP_EOL;
        $modelMore .= PHP_EOL;
        $modelMore .= "  Map<String, dynamic> toJson() {";
        $modelMore .= PHP_EOL;
        $modelMore .= "    final Map<String, dynamic> data = new Map<String, dynamic>();";
        $modelMore .= PHP_EOL;
        $modelMore .= "    data['ar'] = this.ar;";
        $modelMore .= PHP_EOL;
        $modelMore .= "    data['en'] = this.en;";
        $modelMore .= PHP_EOL;
        $modelMore .= "    return data;";
        $modelMore .= PHP_EOL;
        $modelMore .= "  }";
        $modelMore .= PHP_EOL;
        $modelMore .= PHP_EOL;
        $modelMore .= "}";
        $modelMore .= PHP_EOL;
        return $modelMore;
    }

    private function tableData(): string
    {
        $tableData = "";
        foreach($this->cols as $key=>$item){
            if($key!== 0){
                $tableData .= "    ";
            }
            if($item['type'] === 'json' && ($item['name']== 'name' || $item['name']== 'title'|| $item['name']== 'description')){
                $tableData .= "if (this.".$this->handelName($item['name'])." != null) {";
                $tableData .= PHP_EOL;
                $tableData .= "      data['".$item['name']."'] = this.".$this->handelName($item['name'])."!.toJson();";
                $tableData .= PHP_EOL;
                $tableData .= "    }";
            }
            else {
                $tableData .= "data['".$item['name']."'] = this.".$this->handelName($item['name']).";";
            }

            if($key!== count($this->cols)-1){
                $tableData .= PHP_EOL;
            }
        }
        return $tableData;
    }

    private function tableJson(): string
    {
        $tableJson = "";
        foreach($this->cols as $key=>$item){
            if($key!== 0){
                $tableJson .= "    ";
            }
            if($item['type'] === 'json' && ($item['name']== 'name' ||$item['name']== 'title'|| $item['name']== 'description')){
                $tableJson .= $this->handelName($item['name'])." = json['".$item['name']."'] != null";
                $tableJson .= PHP_EOL;
                $tableJson .= "      ? new Name.fromJson(json['".$item['name']."'])";
                $tableJson .= PHP_EOL;
                $tableJson .= "      : null;";
            }
            else {
                $tableJson .= $this->handelName($item['name'])." = json['".$item['name']."'];";
            }

            if($key!== count($this->cols)-1){
                $tableJson .= PHP_EOL;
            }
        }
        return $tableJson;
    }

    private function tableThis(): string {
        $tableThis = "";
        foreach($this->cols as $key=>$item) {
            if($key!== 0) {
                $tableThis .= "    ";
            }
            $tableThis .= "this.".$this->handelName($item['name']) .',';

            if($key!== count($this->cols)-1){
                $tableThis .= PHP_EOL;
            }
        }

        return $tableThis;
    }

    private function tableFields(): string
    {
        $tableFields = "  ";
        foreach($this->cols as $key=>$item){
            if($key!== 0){
                $tableFields .= "    ";
            }
            if($item['type'] === 'int'){
                $tableFields .= "int? ".$this->handelName($item['name']).';';
            }
            else if($item['type'] === 'relation'){
                $tableFields .= "int? ".$this->handelName($item['name']).';';
            }
            else if($item['type'] === 'boolean'){
                $tableFields .= "bool? ".$this->handelName($item['name']).';';
            }
            else if($item['type'] === 'json' && ($item['name']== 'name' ||$item['name']== 'title'|| $item['name']== 'description')){
                $tableFields .= "Name? ".$this->handelName($item['name']).';';
                $this->hasJson = true;
            }
            else {
                if($item['name'] === 'id'){
                    $tableFields .= "int? ".$this->handelName($item['name']).';';
                }
                else {
                    $tableFields .= "String? ".$this->handelName($item['name']).';';
                }
            }

            if($key!== count($this->cols)-1){
                $tableFields .= PHP_EOL;
            }
        }
        return $tableFields;
    }

    private function handelName($name): string
    {
        if($name === 'for'){
            $name = 'for_';
        }
        return $name;
    }

}
