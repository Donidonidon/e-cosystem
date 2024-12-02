<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Cuti;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CutiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CutiResource\RelationManagers;

class CutiResource extends Resource
{
    protected static ?string $model = Cuti::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $schema = [
            Forms\Components\Section::make('FormCuti')
                ->schema([
                    Forms\Components\DatePicker::make('start_date')
                        ->required(),
                    Forms\Components\DatePicker::make('end_date')
                        ->required(),
                    Forms\Components\Textarea::make('reason')
                        ->required()
                        ->columnSpanFull(),
                ])
        ];

        if (Auth::user()->hasRole('super_admin') || Auth::user()->hasRole('acc_cuti')) {
            array_push($schema, Forms\Components\Section::make('Approval')
                ->schema([
                    Forms\Components\Toggle::make('approved_by_hrd'),
                    Forms\Components\Toggle::make('approved_by_leader'),
                    Forms\Components\Toggle::make('approved_by_direksi'),
                    Forms\Components\Textarea::make('notes')
                        ->columnSpanFull(),
                ]));
        }

        return $form->schema($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $is_super_admin = Auth::user()->hasRole('super_admin'); //emang merah error tapi works
                $is_acc = Auth::user()->hasRole('acc_cuti'); //emang merah error tapi works
                if (!$is_super_admin && !$is_acc) {
                    $query->where('user_id', Auth::user()->id);
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.profile.jabatan.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'pending' => 'warning',
                    }),
                Tables\Columns\IconColumn::make('approved_by_hrd')
                    ->boolean(),
                Tables\Columns\IconColumn::make('approved_by_leader')
                    ->boolean(),
                Tables\Columns\IconColumn::make('approved_by_direksi')
                    ->boolean(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCutis::route('/'),
            'create' => Pages\CreateCuti::route('/create'),
            'edit' => Pages\EditCuti::route('/{record}/edit'),
        ];
    }
}
