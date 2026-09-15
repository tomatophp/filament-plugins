<?php

namespace TomatoPHP\FilamentPlugins\Resources\TableResource\Pages;

use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use TomatoPHP\FilamentPlugins\Pages\Plugins;
use TomatoPHP\FilamentPlugins\Resources\TableResource;

class ListTables extends ListRecords
{
    protected static string $resource = TableResource::class;

    public function mount(): void
    {
        if (! request()->has('module')) {
            $this->redirect(Plugins::getUrl());

            return;
        }

        session()->put('current_module', request()->query('module'));

        parent::mount();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label(trans('filament-plugins::messages.tables.actions.create'))
                ->url(fn (): string => TableResource::getUrl('create', ['module' => session()->get('current_module')])),
        ];
    }
}
