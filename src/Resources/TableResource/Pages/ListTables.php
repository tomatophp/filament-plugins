<?php

namespace TomatoPHP\FilamentPlugins\Resources\TableResource\Pages;

use TomatoPHP\FilamentPlugins\Resources\TableResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTables extends ListRecords
{
    protected static string $resource = TableResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create')
                ->label(
                trans('filament-plugins::messages.plugins.create_table'))
                ->url(route('filament.'.filament()->getCurrentPanel()->getId().'.resources.tables.create', ['module' => request()->get('module')]))
        ];
    }
}
