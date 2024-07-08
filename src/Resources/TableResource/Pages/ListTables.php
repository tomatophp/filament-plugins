<?php

namespace TomatoPHP\FilamentPlugins\Resources\TableResource\Pages;

use Filament\Tables\Table;
use TomatoPHP\FilamentPlugins\Resources\TableResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTables extends ListRecords
{
    protected static string $resource = TableResource::class;


    public function mount(): void
    {
        if(!request()->has('module')){
            $this->redirect(route('filament.'.filament()->getCurrentPanel()->getId().'.pages.plugins'));
        }

        if(session()->has('current_module')){
            session()->forget('current_module');
        }

        session()->put('current_module', request()->get('module'));
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create')
                ->label(trans('filament-plugins::messages.tables.actions.create'))
                ->url(route('filament.'.filament()->getCurrentPanel()->getId().'.resources.tables.create', ['module' => request()->get('module')]))
        ];
    }
}
