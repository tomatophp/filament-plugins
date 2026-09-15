<?php

namespace TomatoPHP\FilamentPlugins\Services;

use Illuminate\Support\Facades\File;
use Nwidart\Modules\Module;

class ModulePaths
{
    /**
     * The folder new modules are generated in and ZIP files are extracted to.
     */
    public static function modulesPath(string $relativePath = ''): string
    {
        $path = rtrim((string) config('modules.paths.modules', base_path('Modules')), '/\\');

        return $path.($relativePath !== '' ? DIRECTORY_SEPARATOR.ltrim($relativePath, '/\\') : '');
    }

    public static function usesSourceFolder(Module $module): bool
    {
        return File::isDirectory($module->getPath().DIRECTORY_SEPARATOR.'src');
    }

    /**
     * The folder that holds the module classes (`app/` for generated modules, `src/` for packages).
     */
    public static function appPath(Module $module, string $relativePath = ''): string
    {
        $root = $module->getPath().DIRECTORY_SEPARATOR.(static::usesSourceFolder($module) ? 'src' : 'app');

        return $root.($relativePath !== '' ? DIRECTORY_SEPARATOR.trim(str_replace('\\', '/', $relativePath), '/') : '');
    }

    public static function appNamespace(Module $module, string $relativeNamespace = ''): string
    {
        $relativeNamespace = trim(str_replace('/', '\\', $relativeNamespace), '\\');
        $base = trim((string) config('modules.namespace', 'Modules'), '\\');

        if (static::usesSourceFolder($module)) {
            $info = json_decode((string) File::get($module->getPath().DIRECTORY_SEPARATOR.'module.json'));
            $provider = $info->providers[0] ?? null;

            if (filled($provider)) {
                $base = (string) str($provider)->explode('\\')->first();
            }
        }

        return trim("{$base}\\{$module->getStudlyName()}\\{$relativeNamespace}", '\\');
    }

    public static function viewsPath(Module $module, string $relativePath = ''): string
    {
        $root = $module->getPath().DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'views';

        return $root.($relativePath !== '' ? DIRECTORY_SEPARATOR.trim($relativePath, '/\\') : '');
    }
}
