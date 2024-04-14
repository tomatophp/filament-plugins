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
        return 'Table Columns';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
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
                    ->default(255)
                    ->maxLength(255),
                Forms\Components\TextInput::make('default')
                    ->maxLength(255),
                Forms\Components\Toggle::make('nullable')->default(true),
                Forms\Components\Toggle::make('unsigned'),
                Forms\Components\Toggle::make('auto_increment'),
                Forms\Components\Toggle::make('primary'),
                Forms\Components\Toggle::make('unique'),
                Forms\Components\Toggle::make('index'),
                Forms\Components\Toggle::make('foreign')
                    ->afterStateUpdated(function(Forms\Set $set, Forms\Get $get) {
                        if($get('foreign') === true) {
                            $set('type', 'bigint');
                            $set('unsigned', true);
                        }
                        else {
                            $set('type', null);
                            $set('unsigned', false);
                        }

                    })
                    ->live(),
                Forms\Components\TextInput::make('foreign_table')
                    ->required()
                    ->columnSpan(2)
                    ->hidden(fn(Forms\Get $get) => !$get('foreign')),
                Forms\Components\TextInput::make('foreign_col')
                    ->required()
                    ->columnSpan(2)
                    ->hidden(fn(Forms\Get $get) => !$get('foreign')),
                Forms\Components\Toggle::make('foreign_on_delete_cascade')
                    ->required()
                    ->hidden(fn(Forms\Get $get) => !$get('foreign')),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('id')
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\BooleanColumn::make('nullable'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
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
                    ->label('Add Column'),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('id')
                        ->color('info')
                        ->requiresConfirmation()
                        ->label('Add Id')
                        ->icon('heroicon-s-plus')
                        ->action(function(){
                            $this->ownerRecord->tableCols()->create([
                                'name' => 'id',
                                'type' => 'bigint',
                                'unsigned' => true,
                                'auto_increment' => true,
                                'primary' => true,
                            ]);
                        }),
                    Tables\Actions\Action::make('timestamps')
                        ->color('info')
                        ->requiresConfirmation()
                        ->icon('heroicon-s-plus')
                        ->label('Add Timestamps')
                        ->action(function(){
                            $this->ownerRecord->tableCols()->createMany(
                                [
                                    [
                                        'name' => 'created_at',
                                        'type' => 'datetime',
                                        'nullable' => true,
                                    ],
                                    [
                                        'name' => 'updated_at',
                                        'type' => 'datetime',
                                        'nullable' => true,
                                    ],
                                ]
                            );
                        }),
                    Tables\Actions\Action::make('soft_deletes')
                        ->color('info')
                        ->requiresConfirmation()
                        ->icon('heroicon-s-plus')
                        ->label('Add Soft Deletes')
                        ->action(function(){
                            $this->ownerRecord->tableCols()->create(
                                [
                                    'name' => 'deleted_at',
                                    'type' => 'datetime',
                                    'nullable' => true,
                                ]
                            );
                        })
                ]),


            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
