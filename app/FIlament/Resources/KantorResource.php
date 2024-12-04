<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KantorResource\Pages;
use App\Filament\Resources\KantorResource\RelationManagers;
use App\Models\Kantor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Humaidem\FilamentMapPicker\Fields\OSMMap;


class KantorResource extends Resource
{
    protected static ?string $model = Kantor::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?int $navigationSort = 6;

    public static ?string $navigationGroup = 'Absensi & Cuti';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                OSMMap::make('location')
                                    ->label('Location')
                                    ->showMarker()
                                    ->draggable()
                                    ->extraControl([
                                        'zoomDelta'           => 1,
                                        'zoomSnap'            => 0.25,
                                        'wheelPxPerZoomLevel' => 60
                                    ])
                                    ->afterStateHydrated(function (Forms\Get $get, Forms\Set $set, $record) {
                                        if ($record) {
                                            $latitude = $record->latitude;
                                            $longitude = $record->longitude;

                                            if ($latitude && $longitude) {
                                                $set('location', ['lat' => $latitude, 'lng' => $longitude]);
                                            }
                                        }
                                    })
                                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                        $set('latitude', $state['lat']);
                                        $set('longitude', $state['lng']);
                                    })
                                    ->tilesUrl('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'),
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('latitude')
                                            ->default(0)
                                            ->required()
                                            ->numeric(),
                                        Forms\Components\TextInput::make('longitude')
                                            ->default(0)
                                            ->required()
                                            ->numeric(),
                                    ])->columns(2)
                            ])
                    ]),
                Forms\Components\TextInput::make('radius')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('latitude')
                    ->sortable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('radius')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKantors::route('/'),
            'create' => Pages\CreateKantor::route('/create'),
            'edit' => Pages\EditKantor::route('/{record}/edit'),
        ];
    }
}
