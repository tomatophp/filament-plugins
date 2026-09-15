<?php

namespace Acme\Beta;

use Filament\Contracts\Plugin;
use Filament\Panel;

/**
 * A third-party plugin whose second namespace segment (`Beta`) matches a scanned vendor module.
 */
class BetaPlugin implements Plugin
{
    public static function make(): self
    {
        return new self;
    }

    public function getId(): string
    {
        return 'acme-beta';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([BetaResource::class]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
