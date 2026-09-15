<?php

namespace TomatoPHP\FilamentPlugins\Resources\TableResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TableColsRelationManager extends RelationManager
{
    protected static string $relationship = 'tableCols';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return trans('filament-plugins::messages.tables.columns');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(trans('filament-plugins::messages.tables.form.name'))
                    ->required()
                    ->maxLength(255),
                Select::make('type')
                    ->label(trans('filament-plugins::messages.tables.form.type'))
                    ->searchable()
                    ->required()
                    ->default('string')
                    ->options([
                        'int' => 'int',
                        'string' => 'varchar',
                        'bigint' => 'bigint',
                        'boolean' => 'boolean',
                        'text' => 'text',
                        'longText' => 'longText',
                        'char' => 'char',
                        'flot' => 'flot',
                        'double' => 'double',
                        'json' => 'json',
                        'enum' => 'enum',
                        'jsonb' => 'jsonb',
                        'date' => 'date',
                        'time' => 'time',
                        'datetime' => 'datetime',
                        'timestamps' => 'timestamps',
                    ]),
                TextInput::make('length')
                    ->label(trans('filament-plugins::messages.tables.form.lenth'))
                    ->default(255)
                    ->maxLength(255),
                TextInput::make('default')
                    ->maxLength(255),
                Toggle::make('nullable')
                    ->label(trans('filament-plugins::messages.tables.form.nullable'))
                    ->default(true),
                Toggle::make('unsigned')
                    ->label(trans('filament-plugins::messages.tables.form.unsigned')),
                Toggle::make('auto_increment')
                    ->label(trans('filament-plugins::messages.tables.form.auto_increment')),
                Toggle::make('primary')
                    ->label(trans('filament-plugins::messages.tables.form.primary')),
                Toggle::make('unique')
                    ->label(trans('filament-plugins::messages.tables.form.unique')),
                Toggle::make('index')
                    ->label(trans('filament-plugins::messages.tables.form.index')),
                Toggle::make('foreign')
                    ->label(trans('filament-plugins::messages.tables.form.foreign'))
                    ->afterStateUpdated(function (Set $set, Get $get) {
                        if ($get('foreign') === true) {
                            $set('type', 'bigint');
                            $set('unsigned', true);
                        } else {
                            $set('type', null);
                            $set('unsigned', false);
                        }
                    })
                    ->live(),
                TextInput::make('foreign_table')
                    ->label(trans('filament-plugins::messages.tables.form.foreign_table'))
                    ->required()
                    ->columnSpan(2)
                    ->hidden(fn (Get $get) => ! $get('foreign')),
                TextInput::make('foreign_col')
                    ->label(trans('filament-plugins::messages.tables.form.foreign_col'))
                    ->required()
                    ->columnSpan(2)
                    ->hidden(fn (Get $get) => ! $get('foreign')),
                Toggle::make('foreign_on_delete_cascade')
                    ->label(trans('filament-plugins::messages.tables.form.foreign_on_delete_cascade'))
                    ->required()
                    ->hidden(fn (Get $get) => ! $get('foreign')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label(trans('filament-plugins::messages.tables.form.name')),
                TextColumn::make('type')
                    ->label(trans('filament-plugins::messages.tables.form.type')),
                IconColumn::make('nullable')
                    ->label(trans('filament-plugins::messages.tables.form.nullable'))
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label(trans('filament-plugins::messages.tables.form.type'))
                    ->searchable()
                    ->options([
                        'int' => 'int',
                        'string' => 'varchar',
                        'bigint' => 'bigint',
                        'boolean' => 'boolean',
                        'text' => 'text',
                        'longText' => 'longText',
                        'char' => 'char',
                        'flot' => 'flot',
                        'double' => 'double',
                        'json' => 'json',
                        'enum' => 'enum',
                        'jsonb' => 'jsonb',
                        'date' => 'date',
                        'time' => 'time',
                        'datetime' => 'datetime',
                        'timestamps' => 'timestamps',
                    ]),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(trans('filament-plugins::messages.tables.actions.columns')),
                ActionGroup::make([
                    Action::make('id')
                        ->color('info')
                        ->requiresConfirmation()
                        ->label(trans('filament-plugins::messages.tables.actions.add-id'))
                        ->icon('heroicon-s-plus')
                        ->action(function () {
                            $this->ownerRecord->tableCols()->create([
                                'name' => 'id',
                                'type' => 'bigint',
                                'unsigned' => true,
                                'auto_increment' => true,
                                'primary' => true,
                            ]);
                        }),
                ]),

            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('order')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
