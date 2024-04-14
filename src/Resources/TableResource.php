<?php

namespace TomatoPHP\FilamentPlugins\Resources;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Nwidart\Modules\Facades\Module;
use TomatoPHP\FilamentPlugins\Resources\TableResource\Pages;
use TomatoPHP\FilamentPlugins\Resources\TableResource\RelationManagers;
use TomatoPHP\FilamentPlugins\Models\Table as TableModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use TomatoPHP\FilamentPlugins\Services\CRUDGenerator;

class TableResource extends Resource
{
    protected static ?string $model = TableModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->columnSpan(2)
                    ->required()
                    ->maxLength(255),
            ])->viewData([
                'module' => request()->get('module'),
            ]);
    }


    public static function table(Table $table): Table
    {
        $query = $table->getQuery();
        if(request()->has('module')){
            $query->where('module', request()->get('module'));
        }
        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('migrated')
                    ->boolean(),
                Tables\Columns\IconColumn::make('generated')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
                Tables\Actions\Action::make('migrate')
                    ->requiresConfirmation()
                    ->tooltip('Migrate')
                    ->color('info')
                    ->iconButton()
                    ->icon('heroicon-s-circle-stack')
                    ->action(function (TableModel $record){
                        $record->migrate();
                        Notification::make()
                            ->title('Migrated')
                            ->body('Table migrated successfully.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('generate')
                    ->tooltip('Generate')
                    ->color('info')
                    ->iconButton()
                    ->icon('heroicon-s-home-modern')
                    ->form([
                        Forms\Components\Select::make('type')
                            ->searchable()
                            ->options([
                                'migrate' => 'Migrate',
                                'model' => 'Model',
                                'resource' => 'Resource',
                                'page' => 'Page',
                                'widget' => 'Widget',
//                                'crud' => 'CRUD',
//                                'controller' => 'Controller',
//                                'request' => 'Form Request',
//                                'view' => 'CRUD Views',
//                                'route' => 'Web Routes',
//                                'api' => 'API Routes',
//                                'json' => 'JSON Resource'
                            ])
                    ])
                    ->action(function (TableModel $record, array $data){
                        if((!Schema::hasTable($record->name)) && $data['type'] !== 'migrate'){
                            Notification::make()
                                ->title('Error')
                                ->body('Table does not exist please run migrate.')
                                ->danger()
                                ->send();
                            return;
                        }
                        elseif ($data['type'] === 'migrate'){
                            $module = Module::find($record->module);
                            $module->enable();

                            Artisan::call('migrate');

                            sleep(1);

                            Notification::make()
                                ->title('Migrated')
                                ->body('Table migrated successfully.')
                                ->success()
                                ->send();
                            return;
                        }

                        $checkIfModelExists = File::exists(module_path($record->module, '/app/Models/' . str($record->name)->title()->singular() . '.php'));


                        if((!$checkIfModelExists) && in_array($data['type'], ['resource', 'page', 'widget'])){
                            Notification::make()
                                ->title('Error')
                                ->body('Model does not exist please generate model first.')
                                ->danger()
                                ->send();
                            return;
                        }

                        if($data['type'] === 'resource'){
                            Artisan::call('filament-plugins:resource ' . str($record->name)->title()->singular() .' ' . $record->module . ' --generate');
                        }
                        if($data['type'] === 'page'){
                            Artisan::call('filament-plugins:page ' . str($record->name)->title()->singular() .'Page ' . $record->module);
                        }
                        if($data['type'] === 'widget'){
                            Artisan::call('filament-plugins:widget ' . str($record->name)->title()->singular() .'Widget ' . $record->module . ' --resource=' . str($record->name)->title()->singular() . 'Resource --stats-overview --panel='.filament()->getCurrentPanel()->getId());
                        }

                        $controllers = $data['type'] === 'controller';
                        $request = $data['type'] === 'request';
                        $models = $data['type'] === 'model';
                        $views = $data['type'] === 'views';
                        $routes = $data['type'] === 'routes';
                        $apiRoutes = $data['type'] === 'apiRoutes';
                        $json = $data['type'] === 'json';

                        $generator = new CRUDGenerator(
                            table: $record,
                            migration: false,
                            menu: false,
                            tables: false,
                            controllers: $controllers,
                            request: $request,
                            models: $models,
                            views: $views,
                            routes: $routes,
                            apiRoutes: $apiRoutes,
                            json: $json,
                        );

                        $generator->generate();

                        Notification::make()
                            ->title('Generated')
                            ->body('Table generated successfully.')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TableColsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTables::route('/'),
            'create' => Pages\CreateTable::route('/create/'),
            'edit' => Pages\EditTable::route('/{record}/edit/'),
        ];
    }
}
