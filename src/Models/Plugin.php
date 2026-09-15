<?php

namespace TomatoPHP\FilamentPlugins\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Nwidart\Modules\Facades\Module;
use Sushi\Sushi;

class Plugin extends Model
{
    use Sushi;

    protected $schema = [
        'module_name' => 'string',
        'name' => 'string',
        'description' => 'string',
        'color' => 'string',
        'placeholder' => 'string',
        'icon' => 'string',
        'version' => 'string',
        'docs' => 'string',
        'github' => 'string',
        'active' => 'boolean',
        'providers' => 'string',
        'type' => 'string',
    ];

    public function getRows(): array
    {
        $plugins = collect(Module::all())
            ->filter(function ($module) {
                $file = $module->getPath().DIRECTORY_SEPARATOR.'module.json';

                return File::exists($file) && isset(json_decode(File::get($file))->title);
            })
            ->map(function ($module) {
                $info = json_decode(File::get($module->getPath().DIRECTORY_SEPARATOR.'module.json'));

                return [
                    'module_name' => $info->name,
                    'name' => json_encode($info->title),
                    'description' => json_encode($info->description ?? ''),
                    'color' => $info->color ?? null,
                    'placeholder' => $info->placeholder ?? null,
                    'version' => $info->version ?? null,
                    'type' => $info->type ?? null,
                    'icon' => $info->icon ?? null,
                    'github' => $info->github ?? null,
                    'docs' => $info->docs ?? null,
                    'active' => $module->isEnabled(),
                    'providers' => isset($info->providers) ? json_encode($info->providers) : null,
                ];
            })->toArray();

        return array_values($plugins);
    }
}
