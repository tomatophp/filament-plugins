<?php

namespace TomatoPHP\FilamentPlugins\Pages;

use Composer\Autoload\ClassLoader;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Nwidart\Modules\Facades\Module;
use TomatoPHP\FilamentIcons\Components\IconPicker;
use TomatoPHP\FilamentPlugins\Models\Plugin;
use TomatoPHP\FilamentPlugins\Services\PluginGenerator;
use TomatoPHP\FilamentPlugins\Services\PublishPackage;

class Plugins extends Page implements HasTable
{
    use InteractsWithTable;

    protected $listeners = ['pluginRefresh' => '$refresh'];

    public static ?string $navigationIcon = 'heroicon-o-squares-plus';
    public static string $view = 'filament-plugins::pages.plugins';

    public function getTitle(): string
    {
        return trans('filament-plugins::messages.plugins.title');
    }

    public static function getNavigationLabel(): string
     {
         return trans('filament-plugins::messages.plugins.title');
     }

    public static function getNavigationGroup(): ?string
    {
        return trans('filament-plugins::messages.group');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Plugin::query())
            ->content(function () {
                return view('filament-plugins::pages.table');
            })
            ->paginationPageOptions([
                9,
                18,
                36,
                72,
            ])
            ->columns([
                TextColumn::make('name')
                    ->label(trans('filament-plugins::messages.plugins.form.name'))
                    ->searchable(),
            ]);
    }

    public function disableAction(): Action
    {
        return Action::make('disable')
            ->iconButton()
            ->icon('heroicon-s-x-circle')
            ->color('danger')
            ->tooltip(trans('filament-plugins::messages.plugins.actions.disable'))
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                $module = Module::find($arguments['item']['module_name']);
                $module?->disable();

                Notification::make()
                    ->title(trans('filament-plugins::messages.plugins.notifications.disabled.title'))
                    ->body(trans('filament-plugins::messages.plugins.notifications.disabled.body'))
                    ->success()
                    ->send();

                $this->js('window.location.reload()');
            });
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->visible(function (array $arguments) {
                $module = Module::find($arguments['item']['module_name']);
                if(str($module->getPath())->contains('vendor')){
                    return false;
                }
                else {
                    return true;
                }
            })
            ->iconButton()
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->tooltip(trans('filament-plugins::messages.plugins.actions.delete'))
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                $module = Module::find($arguments['item']['module_name']);
                $module?->delete();

                Notification::make()
                    ->title(trans('filament-plugins::messages.plugins.notifications.deleted.title'))
                    ->body(trans('filament-plugins::messages.plugins.notifications.deleted.body'))
                    ->success()
                    ->send();

                $this->js('window.location.reload()');
            });
    }

    public function activeAction(): Action
    {
        return Action::make('active')
            ->iconButton()
            ->icon('heroicon-s-check-circle')
            ->tooltip(trans('filament-plugins::messages.plugins.actions.active'))
            ->color('success')
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                if(!class_exists(json_decode($arguments['item']['providers'])[0])){
                    Notification::make()
                        ->title(trans('filament-plugins::messages.plugins.notifications.autoload.title'))
                        ->body(trans('filament-plugins::messages.plugins.notifications.autoload.body'))
                        ->danger()
                        ->send();
                    return;
                }
                $module = Module::find($arguments['item']['module_name']);
                $module?->enable();

                Notification::make()
                    ->title(trans('filament-plugins::messages.plugins.notifications.enabled.title'))
                    ->body(trans('filament-plugins::messages.plugins.notifications.enabled.body'))
                    ->success()
                    ->send();

                $this->js('window.location.reload()');

            });
    }

    public function getHeaderActions(): array
    {
        if((bool)config('filament-plugins.allow_create')){
            return [
                Action::make('create')
                    ->label(trans('filament-plugins::messages.plugins.create'))
                    ->icon('heroicon-o-plus')
                    ->form([
                        TextInput::make('name')
                            ->label(trans('filament-plugins::messages.plugins.form.name'))
                            ->placeholder(trans('filament-plugins::messages.plugins.form.name-placeholder'))
                            ->required(),
                        Textarea::make('description')
                            ->label(trans('filament-plugins::messages.plugins.form.description'))
                            ->placeholder(trans('filament-plugins::messages.plugins.form.description-placeholder'))
                            ->required(),
                        ColorPicker::make('color')
                            ->label(trans('filament-plugins::messages.plugins.form.color'))
                            ->required(),
                        IconPicker::make('icon')
                            ->label(trans('filament-plugins::messages.plugins.form.icon'))
                            ->required()
                    ])
                    ->action(fn (array $data) => $this->createPlugin($data)),
                Action::make('import')
                    ->label(trans('filament-plugins::messages.plugins.import'))
                    ->icon('heroicon-o-arrow-up-on-square')
                    ->form([
                        FileUpload::make('file')
                            ->label(trans('filament-plugins::messages.plugins.form.file'))
                            ->acceptedFileTypes(['application/zip'])
                            ->required()
                            ->storeFiles(false)
                    ])
                    ->action(fn (array $data) => $this->importPlugin($data)),
            ];
        }

        return [];

    }

    public function importPlugin(array $data)
    {
        $zip = new \ZipArchive();
        $res = $zip->open($data['file']->getRealPath());

        if ($res === true) {
            $zip->extractTo(base_path('Modules'));
            if(File::exists(base_path('Modules/__MACOSX'))){
                File::deleteDirectory(base_path('Modules/__MACOSX'));
            }

            $zip->close();

            Notification::make()
                ->title(trans('filament-plugins::messages.plugins.notifications.import.title'))
                ->body(trans('filament-plugins::messages.plugins.notifications.import.body'))
                ->success()
                ->send();

            $this->js('window.location.reload()');

        }
    }

    public function createPlugin(array $data)
    {
        $checkIfPluginExists = Module::find(Str::of($data['name'])->camel()->ucfirst()->toString());
        if($checkIfPluginExists){
            Notification::make()
                ->title(trans('filament-plugins::messages.plugins.notifications.exists.title'))
                ->body(trans('filament-plugins::messages.plugins.notifications.exists.body'))
                ->danger()
                ->send();
            return;
        }

        $generator = new PluginGenerator(
            $data['name'],
            $data['description'],
            $data['color'],
            $data['icon']
        );
        $generator->generate();

        Notification::make()
            ->title(trans('filament-plugins::messages.plugins.notifications.created.title'))
            ->body(trans('filament-plugins::messages.plugins.notifications.created.body'))
            ->success()
            ->send();
    }
}
