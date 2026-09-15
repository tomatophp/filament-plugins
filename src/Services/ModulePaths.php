<?php

namespace TomatoPHP\FilamentPlugins\Services;

use Illuminate\Support\Facades\File;
use Nwidart\Modules\Facades\Module as Modules;
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

    /**
     * A module this package manages: it lives in the modules folder and is not a vendor library.
     *
     * With `modules.scan` enabled every TomatoPHP package in vendor/ (they all ship a module.json of
     * type `lib`) is also an nwidart module. Those are never enabled or disabled from the Plugins page.
     */
    public static function isManaged(Module $module): bool
    {
        $path = static::normalize($module->getPath());
        $root = static::normalize(static::modulesPath());

        if (! str_starts_with($path.'/', $root.'/')) {
            return false;
        }

        $file = $module->getPath().DIRECTORY_SEPARATOR.'module.json';
        $info = File::exists($file) ? json_decode((string) File::get($file)) : null;

        return ($info->type ?? null) !== 'lib';
    }

    /**
     * The managed module whose namespace holds the given class, if any.
     */
    public static function moduleForClass(string $class): ?Module
    {
        foreach (Modules::all() as $module) {
            if (static::isManaged($module) && str_starts_with($class, static::appNamespace($module).'\\')) {
                return $module;
            }
        }

        return null;
    }

    protected static function normalize(string $path): string
    {
        return rtrim(str_replace('\\', '/', $path), '/');
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
