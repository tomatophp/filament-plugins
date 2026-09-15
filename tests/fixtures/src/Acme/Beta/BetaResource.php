<?php

namespace Acme\Beta;

use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use TomatoPHP\FilamentPlugins\Tests\Models\User;

class BetaResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'betas';

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name'),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBetas::route('/'),
        ];
    }
}
