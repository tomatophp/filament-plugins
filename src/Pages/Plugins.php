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
    public static string $view = 'filament-plugins::pages.plugins';

    public function getTitle(): string
    {
        return trans('filament-plugins::messages.plugins.plugins');
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('filament-plugins::messages.plugins.settings');
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
            ->tooltip(trans('filament-plugins::messages.plugins.disable'))
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                $module = Module::find($arguments['item']['module_name']);
                $module?->disable();

                Notification::make()
                    ->title(trans('filament-plugins::messages.plugins.plugin_disabled'))
                    ->body(trans('filament-plugins::messages.plugins.the_plugin_has_been_disabled_successfully'))
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
            ->tooltip(trans('filament-plugins::messages.plugins.delete'))
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                $module = Module::find($arguments['item']['module_name']);
                $module?->delete();

                Notification::make()
                    ->title(trans('filament-plugins::messages.plugins.plugin_deleted'))
                    ->body(trans('filament-plugins::messages.plugins.the_plugin_has_been_deleted_successfully'))
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
            ->tooltip(trans('filament-plugins::messages.plugins.active'))
            ->color('success')
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                if(!class_exists(json_decode($arguments['item']['providers'])[0])){
                    Notification::make()
                        ->title(trans('filament-plugins::messages.plugins.you_need_to_run_autoload'))
                        ->body(trans('filament-plugins::messages.plugins.you_need_to_run_composer_dump_autoload_before_activating_the_plugin'))
                        ->danger()
                        ->send();
                    return;
                }
                $module = Module::find($arguments['item']['module_name']);
                $module?->enable();

                Notification::make()
                    ->title(trans('filament-plugins::messages.plugins.plugin_enabled'))
                    ->body(trans('filament-plugins::messages.plugins.the_plugin_has_been_enabled_successfully'))
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
                    ->label(
                    trans('filament-plugins::messages.plugins.create_plugin'))
                    ->icon('heroicon-o-plus')
                    ->form([
                        TextInput::make('name')
                            ->label(
                        trans('filament-plugins::messages.plugins.plugin_name'))
                            ->placeholder(
                        trans('filament-plugins::messages.plugins.e_g_my_plugin'))
                            ->required(),
                        Textarea::make('description')
                            ->label(
                        trans('filament-plugins::messages.plugins.description'))
                            ->placeholder(
                        trans('filament-plugins::messages.plugins.e_g_a_simple_plugin_for_filament'))
                            ->required(),
                        ColorPicker::make('color')->required(),
                        IconPicker::make('icon')->required()
                    ])
                    ->action(fn (array $data) => $this->createPlugin($data)),
                Action::make('import')
                    ->label(
                    trans('filament-plugins::messages.plugins.import_plugin'))
                    ->icon('heroicon-o-arrow-up-on-square')
                    ->form([
                        FileUpload::make('file')
                            ->label(
                        trans('filament-plugins::messages.plugins.plugin_file'))
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
                ->title(trans('filament-plugins::messages.plugins.plugin_uploaded'))
                ->body(trans('filament-plugins::messages.plugins.the_plugin_has_been_uploaded_successfully'))
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
                ->title(trans('filament-plugins::messages.plugins.plugin_already_exists'))
                ->body(trans('filament-plugins::messages.plugins.the_plugin_you_are_trying_to_create_already_exists'))
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
            ->title(trans('filament-plugins::messages.plugins.plugin_generated_success'))
            ->body(trans('filament-plugins::messages.plugins.the_plugin_has_been_generated_successfully'))
            ->success()
            ->send();
    }
}
