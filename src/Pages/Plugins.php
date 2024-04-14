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
use Guava\FilamentIconPicker\Forms\IconPicker;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Nwidart\Modules\Facades\Module;
use TomatoPHP\FilamentPlugins\Models\Plugin;
use TomatoPHP\FilamentPlugins\Services\PluginGenerator;

class Plugins extends Page implements HasTable
{
    use InteractsWithTable;

    protected $listeners = ['pluginRefresh' => '$refresh'];

    public static ?string $navigationIcon = 'heroicon-o-squares-plus';
    public static ?string $navigationGroup = 'Settings';
    public static string $view = 'filament-plugins::pages.plugins';

    public function getTitle(): string
    {
        return 'Plugins';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Plugin::query())
            ->content(function () {
                return view('filament-plugins::pages.table');
            })
            ->columns([
                TextColumn::make('name')->searchable(),
            ]);
    }

    public function disableAction(): Action
    {
        return Action::make('disable')
            ->iconButton()
            ->icon('heroicon-s-x-circle')
            ->color('danger')
            ->tooltip('Disable')
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                $module = Module::find($arguments['item']['module_name']);
                $module?->disable();

                Notification::make()
                    ->title(__("Plugin Disabled"))
                    ->body(__("The plugin has been disabled successfully."))
                    ->success()
                    ->send();

                $this->js('window.location.reload()');
            });
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->iconButton()
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->tooltip('Delete')
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                $module = Module::find($arguments['item']['module_name']);
                $module?->delete();

                Notification::make()
                    ->title(__("Plugin Deleted"))
                    ->body(__("The plugin has been deleted successfully."))
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
            ->tooltip('Active')
            ->color('success')
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                if(!class_exists(json_decode($arguments['item']['providers'])[0])){
                    Notification::make()
                        ->title(__("You need to run autoload"))
                        ->body(__("You need to run composer dump-autoload before activating the plugin."))
                        ->danger()
                        ->send();
                    return;
                }
                $module = Module::find($arguments['item']['module_name']);
                $module?->enable();

                Notification::make()
                    ->title(__("Plugin Enabled"))
                    ->body(__("The plugin has been enabled successfully."))
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
                    ->label('Create Plugin')
                    ->icon('heroicon-o-plus')
                    ->form([
                        TextInput::make('name')
                            ->label('Plugin Name')
                            ->placeholder('e.g. My Plugin')
                            ->required(),
                        Textarea::make('description')
                            ->label('Description')
                            ->placeholder('e.g. A simple plugin for Filament')
                            ->required(),
                        ColorPicker::make('color')->required(),
                        IconPicker::make('icon')->required()
                    ])
                    ->action(fn (array $data) => $this->createPlugin($data)),
                Action::make('import')
                    ->label('Import Plugin')
                    ->icon('heroicon-o-arrow-up-on-square')
                    ->form([
                        FileUpload::make('file')
                            ->label('Plugin File')
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
                ->title(__("Plugin Uploaded"))
                ->body(__("The plugin has been uploaded successfully."))
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
                ->title(__("Plugin Already Exists"))
                ->body(__("The plugin you are trying to create already exists."))
                ->danger()
                ->send();
        }

        $generator = new PluginGenerator(
            $data['name'],
            $data['description'],
            $data['color'],
            $data['icon']
        );
        $generator->generate();

        Notification::make()
            ->title(__("Plugin Generated Success"))
            ->body(__("The plugin has been generated successfully."))
            ->success()
            ->send();
    }
}
