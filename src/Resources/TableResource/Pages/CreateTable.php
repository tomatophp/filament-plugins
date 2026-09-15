<?php

namespace TomatoPHP\FilamentPlugins\Resources\TableResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use TomatoPHP\FilamentPlugins\Resources\TableResource;

class CreateTable extends CreateRecord
{
    protected static string $resource = TableResource::class;

    public ?string $module = null;

    public function getTitle(): string
    {
        return trans('filament-plugins::messages.tables.create');
    }

    public function mount(): void
    {
        $this->module = request()->query('module', session()->get('current_module'));

        parent::mount();
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
            'primary' => true,
        ]);
    }
}
