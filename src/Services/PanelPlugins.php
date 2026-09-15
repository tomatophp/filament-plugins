<?php

namespace TomatoPHP\FilamentPlugins\Services;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;

class PanelPlugins
{
    /**
     * Remove a module plugin and the pages, resources and widgets of its namespace from the panel.
     */
    public static function disable(Panel $panel, Plugin $plugin): Panel
    {
        $namespace = (string) (str(get_class($plugin))->explode('\\')[1] ?? '');

        $remove = Closure::bind(function (string $namespace, string $pluginId): void {
            $keep = fn ($item): bool => $namespace === '' || ! str($item)->contains($namespace);

            $this->resources = array_filter($this->resources, $keep);
            $this->pages = array_filter($this->pages, $keep);
            $this->widgets = array_filter($this->widgets, $keep);
            $this->plugins = array_filter($this->plugins, fn ($item, $key): bool => $key !== $pluginId, ARRAY_FILTER_USE_BOTH);
        }, $panel, Panel::class);

        $remove($namespace, $plugin->getId());

        return $panel;
    }
}
