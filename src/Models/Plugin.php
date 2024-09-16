<?php

namespace TomatoPHP\FilamentPlugins\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;
use Sushi\Sushi;
use TomatoPHP\FilamentPlugins\Facades\FilamentPlugins;
use function Pest\Laravel\json;

class Plugin extends Model
{
    use Sushi;

    protected $schema = [
        'module_name' => 'string',
        'title' => 'json',
        'description' => 'json',
        'color' => 'string',
        'placeholder' => 'string',
        'icon' => 'string',
        'version' => 'string',
        'docs' => 'string',
        'github' => 'string',
        'active' => 'boolean',
        'providers' => 'json',
        'type' => 'string',
    ];


    public function getRows()
    {
        $plugins = collect(Module::all())
            ->filter(function ($module){
                if(File::exists($module->getPath())){
                    $info = json_decode(File::get($module->getPath() . "/module.json"));
                    if(isset($info->title)){
                        return true;
                    }
                }

                return false;
            })
            ->map(function ($module){
                $info = json_decode(File::get($module->getPath() . "/module.json"));
                return [
                    "module_name" => $info->name,
                    "name" => json_encode($info->title),
                    "description" => json_encode($info->description),
                    "color" => $info->color??null,
                    "placeholder" => $info->placeholder??null,
                    "version" => $info->version??null,
                    "type" => $info->type??null,
                    "icon" => $info->icon??null,
                    "github" => isset($info->github)?$info->github:null,
                    "docs" => isset($info->docs)?$info->docs:null,
                    "active" => Module::find($info->name)->isEnabled(),
                    "providers" => isset($info->providers)?json_encode($info->providers):null,
                ];
            })->toArray();

        return array_values($plugins);
    }
}
