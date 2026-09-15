<?php

namespace TomatoPHP\FilamentPlugins\Resources\TableResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use TomatoPHP\FilamentPlugins\Resources\TableResource;

class EditTable extends EditRecord
{
    protected static string $resource = TableResource::class;

    public function getTitle(): string
    {
        return trans('filament-plugins::messages.tables.edit');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
