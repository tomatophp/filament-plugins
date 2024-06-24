<?php

namespace TomatoPHP\FilamentPlugins\Resources\TableResource\Pages;

use TomatoPHP\FilamentPlugins\Resources\TableResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTable extends CreateRecord
{
    protected static string $resource = TableResource::class;

    public function getTitle(): string
    {
        return trans('filament-plugins::messages.tables.create');
    }

    public ?string $module = null;

    public function mount(): void
    {
        if(request()->has('module')){
            $this->module = request()->get('module');
        }

    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['module'] = $this->module;
        return $data;
    }

    protected function afterCreate(): void
    {
        $this->getRecord()->tableCols()->create([
            'name' => 'id',
            'type' => 'bigint',
            'unsigned' => true,
            'auto_increment' => true,
            'primary' => true
        ]);
    }

}
