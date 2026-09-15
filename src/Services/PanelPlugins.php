<?php

namespace TomatoPHP\FilamentPlugins\Services;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;

class PanelPlugins
{
    /**
     * Remove a module plugin and the pages, resources and widgets under its namespace from the panel.
     *
     * Only classes inside `$namespace` (the module namespace, or the plugin's own namespace) are removed,
     * so other plugins on the panel are never touched.
     */
    public static function disable(Panel $panel, Plugin $plugin, ?string $namespace = null): Panel
    {
        $namespace = trim($namespace ?? (string) str(get_class($plugin))->beforeLast('\\'), '\\');

        $remove = Closure::bind(function (string $prefix, string $pluginId): void {
            $keep = fn ($item): bool => ! is_string($item) || ! str_starts_with(ltrim($item, '\\'), $prefix);

            $this->resources = array_filter($this->resources, $keep);
            $this->pages = array_filter($this->pages, $keep);
            $this->widgets = array_filter($this->widgets, $keep);
            $this->plugins = array_filter($this->plugins, fn ($item, $key): bool => $key !== $pluginId, ARRAY_FILTER_USE_BOTH);
        }, $panel, Panel::class);

        $remove($namespace.'\\', $plugin->getId());

        return $panel;
    }
}
