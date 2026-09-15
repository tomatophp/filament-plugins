<?php

namespace Modules\Gamma;

use Filament\Pages\Page;

class GammaPage extends Page
{
    protected static ?string $slug = 'gamma-page';

    protected string $view = 'filament-panels::pages.dashboard';
}
