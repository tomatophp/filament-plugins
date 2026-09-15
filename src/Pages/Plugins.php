<?php

namespace TomatoPHP\FilamentPlugins\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;
use TomatoPHP\FilamentIcons\Components\IconPicker;
use TomatoPHP\FilamentPlugins\FilamentPluginsPlugin;
use TomatoPHP\FilamentPlugins\Models\Plugin;
use TomatoPHP\FilamentPlugins\Services\ModulePaths;
use TomatoPHP\FilamentPlugins\Services\PluginGenerator;
use UnitEnum;
use ZipArchive;

class Plugins extends Page implements HasTable
{
    use InteractsWithTable;

    protected $listeners = ['pluginRefresh' => '$refresh'];

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-plus';

    protected string $view = 'filament-plugins::pages.plugins';

    public function getTitle(): string
    {
        return trans('filament-plugins::messages.plugins.title');
    }

    public static function getNavigationLabel(): string
    {
        return trans('filament-plugins::messages.plugins.title');
    }

    public static function getNavigationGroup(): string|UnitEnum|null
    {
        return trans('filament-plugins::messages.group');
    }

    public function canGenerate(): bool
    {
        return FilamentPluginsPlugin::allows('generator');
    }

    public function canToggle(): bool
    {
        return FilamentPluginsPlugin::allows('toggle');
    }

    public function canDestroy(): bool
    {
        return FilamentPluginsPlugin::allows('destroy');
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
        return Action::make('disableAction')
            ->visible(fn (): bool => $this->canToggle())
            ->iconButton()
            ->icon('heroicon-s-x-circle')
            ->color('danger')
            ->tooltip(trans('filament-plugins::messages.plugins.actions.disable'))
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                Module::find($arguments['module'] ?? '')?->disable();

                Notification::make()
                    ->title(trans('filament-plugins::messages.plugins.notifications.disabled.title'))
                    ->body(trans('filament-plugins::messages.plugins.notifications.disabled.body'))
                    ->success()
                    ->send();

                $this->redirect(static::getUrl());
            });
    }

    public function deleteAction(): Action
    {
        return Action::make('deleteAction')
            ->visible(function (array $arguments): bool {
                if (! $this->canDestroy()) {
                    return false;
                }

                $module = Module::find($arguments['module'] ?? '');

                return $module && ! str($module->getPath())->contains('vendor');
            })
            ->iconButton()
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->tooltip(trans('filament-plugins::messages.plugins.actions.delete'))
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                Module::find($arguments['module'] ?? '')?->delete();

                Notification::make()
                    ->title(trans('filament-plugins::messages.plugins.notifications.deleted.title'))
                    ->body(trans('filament-plugins::messages.plugins.notifications.deleted.body'))
                    ->success()
                    ->send();

                $this->redirect(static::getUrl());
            });
    }

    public function activeAction(): Action
    {
        return Action::make('activeAction')
            ->visible(fn (): bool => $this->canToggle())
            ->iconButton()
            ->icon('heroicon-s-check-circle')
            ->tooltip(trans('filament-plugins::messages.plugins.actions.active'))
            ->color('success')
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                $providers = json_decode((string) ($arguments['providers'] ?? ''), true) ?: [];
                $provider = $providers[0] ?? null;

                if ($provider && ! class_exists($provider)) {
                    Notification::make()
                        ->title(trans('filament-plugins::messages.plugins.notifications.autoload.title'))
                        ->body(trans('filament-plugins::messages.plugins.notifications.autoload.body'))
                        ->danger()
                        ->send();

                    return;
                }

                Module::find($arguments['module'] ?? '')?->enable();

                Notification::make()
                    ->title(trans('filament-plugins::messages.plugins.notifications.enabled.title'))
                    ->body(trans('filament-plugins::messages.plugins.notifications.enabled.body'))
                    ->success()
                    ->send();

                $this->redirect(static::getUrl());
            });
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->visible(fn (): bool => FilamentPluginsPlugin::allows('create'))
                ->label(trans('filament-plugins::messages.plugins.create'))
                ->icon('heroicon-o-plus')
                ->schema([
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
                        ->required(),
                ])
                ->action(fn (array $data) => $this->createPlugin($data)),
            ActionGroup::make([
                Action::make('import')
                    ->visible(fn (): bool => FilamentPluginsPlugin::allows('import'))
                    ->label(trans('filament-plugins::messages.plugins.import'))
                    ->icon('heroicon-o-arrow-up-on-square')
                    ->schema([
                        FileUpload::make('file')
                            ->label(trans('filament-plugins::messages.plugins.form.file'))
                            ->acceptedFileTypes([
                                'application/zip',
                                'application/x-zip',
                                'application/octet-stream',
                                'application/x-zip-compressed',
                            ])
                            ->required()
                            ->storeFiles(false),
                    ])
                    ->action(fn (array $data) => $this->importPlugin($data)),
                Action::make('enable')
                    ->visible(fn (): bool => $this->canToggle())
                    ->requiresConfirmation()
                    ->label(trans('filament-plugins::messages.plugins.enable'))
                    ->icon('heroicon-o-check-circle')
                    ->action(function () {
                        collect(Module::all())->each(fn ($module) => $module->enable());

                        $this->redirect(static::getUrl());
                    }),
                Action::make('disable')
                    ->visible(fn (): bool => $this->canToggle())
                    ->requiresConfirmation()
                    ->label(trans('filament-plugins::messages.plugins.disable'))
                    ->icon('heroicon-o-x-circle')
                    ->action(function () {
                        collect(Module::all())->each(fn ($module) => $module->disable());

                        $this->redirect(static::getUrl());
                    }),
            ]),
        ];
    }

    public function importPlugin(array $data): void
    {
        if (! FilamentPluginsPlugin::allows('import')) {
            return;
        }

        $zip = new ZipArchive;
        $res = $zip->open($data['file']->getRealPath());

        if ($res !== true) {
            return;
        }

        for ($index = 0; $index < $zip->numFiles; $index++) {
            $entry = str_replace('\\', '/', (string) $zip->getNameIndex($index));

            if (str_starts_with($entry, '/') || str_contains($entry, '../')) {
                $zip->close();

                return;
            }
        }

        $zip->extractTo(ModulePaths::modulesPath());
        $zip->close();

        if (File::exists(ModulePaths::modulesPath('__MACOSX'))) {
            File::deleteDirectory(ModulePaths::modulesPath('__MACOSX'));
        }

        Notification::make()
            ->title(trans('filament-plugins::messages.plugins.notifications.import.title'))
            ->body(trans('filament-plugins::messages.plugins.notifications.import.body'))
            ->success()
            ->send();

        $this->redirect(static::getUrl());
    }

    public function createPlugin(array $data): void
    {
        if (! FilamentPluginsPlugin::allows('create')) {
            return;
        }

        $checkIfPluginExists = Module::find(Str::of($data['name'])->camel()->ucfirst()->toString());
        if ($checkIfPluginExists) {
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

        $this->redirect(static::getUrl());
    }
}
