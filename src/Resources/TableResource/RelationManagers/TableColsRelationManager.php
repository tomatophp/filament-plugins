<?php

namespace TomatoPHP\FilamentPlugins\Resources\TableResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TableColsRelationManager extends RelationManager
{
    protected static string $relationship = 'tableCols';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return trans('filament-plugins::messages.tables.columns');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(trans('filament-plugins::messages.tables.form.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
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
                Forms\Components\TextInput::make('length')
                    ->label(trans('filament-plugins::messages.tables.form.lenth'))
                    ->default(255)
                    ->maxLength(255),
                Forms\Components\TextInput::make('default')
                    ->maxLength(255),
                Forms\Components\Toggle::make('nullable')
                    ->label(trans('filament-plugins::messages.tables.form.nullable'))
                    ->default(true),
                Forms\Components\Toggle::make('unsigned')
                    ->label(trans('filament-plugins::messages.tables.form.unsigned')),
                Forms\Components\Toggle::make('auto_increment')
                    ->label(trans('filament-plugins::messages.tables.form.auto_increment')),
                Forms\Components\Toggle::make('primary')
                    ->label(trans('filament-plugins::messages.tables.form.primary')),
                Forms\Components\Toggle::make('unique')
                    ->label(trans('filament-plugins::messages.tables.form.unique')),
                Forms\Components\Toggle::make('index')
                    ->label(trans('filament-plugins::messages.tables.form.index')),
                Forms\Components\Toggle::make('foreign')
                    ->label(trans('filament-plugins::messages.tables.form.foreign'))
                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                        if ($get('foreign') === true) {
                            $set('type', 'bigint');
                            $set('unsigned', true);
                        } else {
                            $set('type', null);
                            $set('unsigned', false);
                        }
                    })
                    ->live(),
                Forms\Components\TextInput::make('foreign_table')
                    ->label(trans('filament-plugins::messages.tables.form.foreign_table'))
                    ->required()
                    ->columnSpan(2)
                    ->hidden(fn (Forms\Get $get) => !$get('foreign')),
                Forms\Components\TextInput::make('foreign_col')
                    ->label(trans('filament-plugins::messages.tables.form.foreign_col'))
                    ->required()
                    ->columnSpan(2)
                    ->hidden(fn (Forms\Get $get) => !$get('foreign')),
                Forms\Components\Toggle::make('foreign_on_delete_cascade')
                    ->label(trans('filament-plugins::messages.tables.form.foreign_on_delete_cascade'))
                    ->required()
                    ->hidden(fn (Forms\Get $get) => !$get('foreign')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('filament-plugins::messages.tables.form.name')),
                Tables\Columns\TextColumn::make('type')
                    ->label(trans('filament-plugins::messages.tables.form.type')),
                Tables\Columns\BooleanColumn::make('nullable')
                    ->label(trans('filament-plugins::messages.tables.form.nullable')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
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
                Tables\Actions\CreateAction::make()
                    ->label(trans('filament-plugins::messages.tables.actions.columns')),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('id')
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
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->defaultSort('order')
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
