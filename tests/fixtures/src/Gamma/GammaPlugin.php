<?php

namespace Modules\Gamma;

use Filament\Contracts\Plugin;
use Filament\Panel;

/**
 * The Filament plugin of the generated `Gamma` module (lives in the module namespace).
 */
class GammaPlugin implements Plugin
{
    public static function make(): self
    {
        return new self;
    }

    public function getId(): string
    {
        return 'modules-gamma';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([GammaPage::class]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
