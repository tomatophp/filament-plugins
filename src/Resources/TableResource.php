<?php

namespace TomatoPHP\FilamentPlugins\Resources;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema as DatabaseSchema;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;
use TomatoPHP\FilamentPlugins\FilamentPluginsPlugin;
use TomatoPHP\FilamentPlugins\Models\Table as TableModel;
use TomatoPHP\FilamentPlugins\Resources\TableResource\Pages\CreateTable;
use TomatoPHP\FilamentPlugins\Resources\TableResource\Pages\EditTable;
use TomatoPHP\FilamentPlugins\Resources\TableResource\Pages\ListTables;
use TomatoPHP\FilamentPlugins\Resources\TableResource\RelationManagers\TableColsRelationManager;
use TomatoPHP\FilamentPlugins\Services\CRUDGenerator;
use TomatoPHP\FilamentPlugins\Services\ModulePaths;

class TableResource extends Resource
{
    protected static ?string $model = TableModel::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    public static function canAccess(): bool
    {
        return FilamentPluginsPlugin::allows('generator') && parent::canAccess();
    }

    public static function getNavigationLabel(): string
    {
        return trans('filament-plugins::messages.tables.title');
    }

    public static function getPluralLabel(): ?string
    {
        return trans('filament-plugins::messages.tables.title').' ['.session()->get('current_module').']';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(trans('filament-plugins::messages.tables.form.name'))
                    ->columnSpan(2)
                    ->required()
                    ->maxLength(255),
                Toggle::make('timestamps')
                    ->label(trans('filament-plugins::messages.tables.form.timestamps'))
                    ->default(true),
                Toggle::make('soft_deletes')
                    ->default(false)
                    ->label(trans('filament-plugins::messages.tables.form.soft_deletes')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                $query->where('module', session()->get('current_module'));
            })
            ->columns([
                TextColumn::make('name')
                    ->label(trans('filament-plugins::messages.tables.form.name'))
                    ->searchable(),
                IconColumn::make('migrated')
                    ->label(trans('filament-plugins::messages.tables.form.migrated'))
                    ->boolean(),
                IconColumn::make('generated')
                    ->label(trans('filament-plugins::messages.tables.form.generated'))
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label(trans('filament-plugins::messages.tables.form.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(trans('filament-plugins::messages.tables.form.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make()
                    ->iconButton(),
                DeleteAction::make()
                    ->iconButton(),
                Action::make('migrate')
                    ->requiresConfirmation()
                    ->tooltip(trans('filament-plugins::messages.tables.actions.migrate'))
                    ->color('info')
                    ->iconButton()
                    ->icon('heroicon-s-circle-stack')
                    ->action(function (TableModel $record) {
                        $record->migrate();
                        Notification::make()
                            ->title(trans('filament-plugins::messages.tables.notifications.migrated.title'))
                            ->body(trans('filament-plugins::messages.tables.notifications.migrated.body'))
                            ->success()
                            ->send();
                    }),
                Action::make('generate')
                    ->tooltip(trans('filament-plugins::messages.tables.actions.generate'))
                    ->color('info')
                    ->iconButton()
                    ->icon('heroicon-s-home-modern')
                    ->schema([
                        Select::make('type')
                            ->searchable()
                            ->required()
                            ->options([
                                'migrate' => 'Migrate',
                                'model' => 'Model',
                                'resource' => 'Resource',
                                'page' => 'Page',
                                'widget' => 'Widget',
                            ]),
                    ])
                    ->action(fn (TableModel $record, array $data) => static::generate($record, $data['type'])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function generate(TableModel $record, string $type): void
    {
        if ((! DatabaseSchema::hasTable($record->name)) && $type !== 'migrate') {
            Notification::make()
                ->title(trans('filament-plugins::messages.tables.notifications.not-migrated.title'))
                ->body(trans('filament-plugins::messages.tables.notifications.not-migrated.body'))
                ->danger()
                ->send();

            return;
        }

        $module = Module::find($record->module);

        if ($type === 'migrate') {
            $module?->enable();

            Artisan::call('migrate', ['--force' => true]);

            Notification::make()
                ->title(trans('filament-plugins::messages.tables.notifications.migrated.title'))
                ->body(trans('filament-plugins::messages.tables.notifications.migrated.body'))
                ->success()
                ->send();

            return;
        }

        $modelName = Str::ucfirst(Str::singular(Str::camel($record->name)));
        $checkIfModelExists = $module && File::exists(ModulePaths::appPath($module, 'Models/'.$modelName.'.php'));

        if ((! $checkIfModelExists) && in_array($type, ['resource', 'page', 'widget'])) {
            Notification::make()
                ->title(trans('filament-plugins::messages.tables.notifications.model.title'))
                ->body(trans('filament-plugins::messages.tables.notifications.model.body'))
                ->danger()
                ->send();

            return;
        }

        $panelId = filament()->getCurrentOrDefaultPanel()->getId();

        match ($type) {
            'resource' => Artisan::call('filament-plugins:resource', [
                'model' => $modelName,
                'module' => $record->module,
                '--generate' => true,
                '--panel' => $panelId,
                '--no-interaction' => true,
            ]),
            'page' => Artisan::call('filament-plugins:page', [
                'name' => $modelName.'Page',
                'module' => $record->module,
                '--panel' => $panelId,
                '--no-interaction' => true,
            ]),
            'widget' => Artisan::call('filament-plugins:widget', [
                'name' => $modelName.'Widget',
                'module' => $record->module,
                '--stats-overview' => true,
                '--panel' => $panelId,
                '--no-interaction' => true,
            ]),
            'model' => (new CRUDGenerator(table: $record, migration: false, models: true))->generate(),
            default => null,
        };

        Notification::make()
            ->title(trans('filament-plugins::messages.tables.notifications.generated.title'))
            ->body(trans('filament-plugins::messages.tables.notifications.generated.body'))
            ->success()
            ->send();
    }

    public static function getRelations(): array
    {
        return [
            TableColsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTables::route('/'),
            'create' => CreateTable::route('/create'),
            'edit' => EditTable::route('/{record}/edit'),
        ];
    }
}
