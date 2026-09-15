<?php

namespace Acme\Alpha;

use Filament\Pages\Page;

class AlphaPage extends Page
{
    protected static ?string $slug = 'alpha-page';

    protected string $view = 'filament-panels::pages.dashboard';
}
