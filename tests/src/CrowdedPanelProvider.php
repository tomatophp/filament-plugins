<?php

namespace TomatoPHP\FilamentPlugins\Tests;

use Acme\Alpha\AlphaPlugin;
use Acme\Beta\BetaPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Session\Middleware\StartSession;
use TomatoPHP\FilamentPlugins\FilamentPluginsPlugin;

/**
 * A panel with other plugins registered before FilamentPluginsPlugin, like a real app.
 */
class CrowdedPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('crowded')
            ->path('crowded')
            ->plugins([
                AlphaPlugin::make(),
                BetaPlugin::make(),
                FilamentPluginsPlugin::make(),
            ])
            ->middleware([
                StartSession::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
