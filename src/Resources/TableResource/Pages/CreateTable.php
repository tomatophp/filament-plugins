<?php

namespace TomatoPHP\FilamentPlugins\Resources\TableResource\Pages;

use TomatoPHP\FilamentPlugins\Resources\TableResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTable extends CreateRecord
{
    protected static string $resource = TableResource::class;

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
}
