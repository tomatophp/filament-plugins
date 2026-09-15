<?php

namespace Acme\Alpha;

use Filament\Contracts\Plugin;
use Filament\Panel;

/**
 * A third-party plugin whose second namespace segment (`Alpha`) matches a scanned vendor module.
 */
class AlphaPlugin implements Plugin
{
    public static function make(): self
    {
        return new self;
    }

    public function getId(): string
    {
        return 'acme-alpha';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([AlphaPage::class]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
