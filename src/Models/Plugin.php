<?php

namespace TomatoPHP\FilamentPlugins\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;
use Sushi\Sushi;

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
        'pages' => 'json',
        'resources' => 'json',
        'widgets' => 'json',
    ];

    public function getRows()
    {
        $getPlugins = [];
        if(File::exists(base_path('Modules'))){
            $getPlugins = collect(File::directories(base_path('Modules')));
            $getPlugins = $getPlugins->filter(function ($item) {
                $json = json_decode(File::get($item . "/module.json"));
                if (isset($json->type) && $json->type === 'plugin'){
                    return true;
                }
                else {
                    return false;
                }
            })->transform(callback: static function($item){
                $info = json_decode(File::get($item . "/module.json"));
                return [
                    "module_name" => $info->name,
                    "name" => json_encode($info->title),
                    "description" => json_encode($info->description),
                    "color" => $info->color,
                    "placeholder" => $info->placeholder,
                    "version" => $info->version,
                    "icon" => $info->icon,
                    "github" => isset($info->github)?$info->github:null,
                    "docs" => isset($info->docs)?$info->docs:null,
                    "active" => Module::find($info->name)->isEnabled(),
                    "providers" => json_encode($info->providers),
                    "resources" => json_encode($info->resources),
                    "pages" => json_encode($info->pages),
                    "widgets" => json_encode($info->widgets),
                ];
            });
        }


        $values = array_values($getPlugins->toArray());
        return $values;
    }
}
