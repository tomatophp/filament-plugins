<?php

namespace TomatoPHP\TomatoPlugins\Services\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait InjectString
{
    public function injectString(string $file, string $find, string $stub, array $replacements=[], int $plus=1): void
    {
        if(File::exists($file) && File::exists($stub)){
            $stubValue = File::get($stub);

            $convertStubToText = Str::of($stubValue);

            foreach($replacements as $key=>$replacement){
                $convertStubToText = $convertStubToText->replace('{{ '.$key.' }}',$replacement);
            }

            $content = \Illuminate\Support\Facades\File::get($file);
            $fileToString = Str::of($content);
            $fileToArray = $fileToString->explode("\n");
            $pos = 0;
            $fileToArray->each(function ($line, $key) use (&$pos, $find, $plus){
                if(Str::contains($line, $find)){
                    $pos = $key + $plus;
                }
            });

            $newArray = array_slice($fileToArray->toArray(), 0, $pos, true) +
                array("value" =>"\n". $convertStubToText->toString()."\n")  +
                array_slice($fileToArray->toArray(), $pos, count($fileToArray->toArray()) - 1, true) ;

            $replaceToArray = explode("\n", $convertStubToText->toString());
            if(!array_search($replaceToArray[0], $newArray)){
                $fileToString = implode("\n", $newArray);
                \Illuminate\Support\Facades\File::put($file, $fileToString);
            }
        }
    }
}
