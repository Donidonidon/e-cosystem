<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Cuti;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Livewire\ExportCutiPdf;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CutiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CutiResource\RelationManagers;

class CutiResource extends Resource
{
    protected static ?string $model = Cuti::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';

    protected static ?string $label = 'Permohonan Cuti'; // Nama tunggal
    protected static ?string $pluralLabel = 'Permohonan Cuti'; // Nama jamak

    protected static ?int $navigationSort = 4;
    public static ?string $navigationGroup = 'Absensi & Cuti';

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
                ])->disabled(fn() => Auth::user()->hasAnyRole(['direksi', 'hrd', 'leader'])),
        ];

        if (Auth::user()->hasAnyRole(['direksi', 'hrd', 'leader'])) {
            array_push($schema, Forms\Components\Section::make('Approval')
                ->schema([
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Toggle::make('approved_by_leader')
                                ->label('Leader')
                                ->onColor('success')
                                ->reactive()
                                ->disabled(fn() => !Auth::user()->hasRole('leader')), // Aktif jika user adalah Leader
                            Forms\Components\Toggle::make('approved_by_direksi')
                                ->label('Direksi')
                                ->onColor('success')
                                ->hint(
                                    'Approval dari Leader diperlukan sebelum disetujui oleh Direksi.' // Tooltip
                                )
                                ->reactive()
                                ->disabled(fn(callable $get) => !$get('approved_by_leader') || !Auth::user()->hasRole('direksi')), // Aktif jika Leader sudah approve dan user adalah Direksi
                            Forms\Components\Toggle::make('approved_by_hrd')
                                ->label('HRD')
                                ->onColor('success')
                                ->hint(
                                    'Approval dari Direksi diperlukan sebelum disetujui oleh HRD.' // Tooltip
                                )
                                ->reactive()
                                ->disabled(fn(callable $get) => !$get('approved_by_direksi') || !Auth::user()->hasRole('hrd')), // Aktif jika Leader sudah approve dan user adalah Direksi
                        ])->columns(3),
                    Forms\Components\Textarea::make('notes')
                        ->label('Catatan')
                        ->default('notes')
                        ->columnSpanFull()
                        ->disabled(fn() => !Auth::user()->hasAnyRole(['direksi', 'hrd', 'leader'])), // Hanya Super Admin atau ACC Cuti yang bisa edit
                ]));
        }

        return $form->schema($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                // $is_super_admin = Auth::user()->hasRole('super_admin'); //emang merah error tapi works
                $is_acc = Auth::user()->hasAnyRole(['direksi', 'hrd', 'leader']); //emang merah error tapi works
                if (!$is_acc) {
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
                    ->label('HRD')
                    ->boolean(),
                Tables\Columns\IconColumn::make('approved_by_leader')
                    ->label('Leader')
                    ->boolean(),
                Tables\Columns\IconColumn::make('approved_by_direksi')
                    ->label('Direksi')
                    ->boolean(),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Catatan'),
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
                Tables\Filters\SelectFilter::make('Jabatan')
                    ->relationship('user.profile.jabatan', 'name')
                    ->label('Jabatan')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Action::make('cetak')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->url(fn(Cuti $record): string => route('cuti.export-pdf', $record))
                    ->openUrlInNewTab()
                    ->disabled(fn(Cuti $record): bool => $record->status === 'pending' || $record->status === 'rejected' && !Auth::user()->hasRole('hrd')),
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
