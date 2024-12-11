<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Cuti;
use Filament\Tables;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;
use App\Livewire\ExportCutiPdf;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Actions\Action as SectionAction;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CutiResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CutiResource\RelationManagers;
use Joaopaulolndev\FilamentPdfViewer\Infolists\Components\PdfViewerEntry;

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
                        ->required()
                        ->label('Start Date'),
                    Forms\Components\DatePicker::make('end_date')
                        ->label('End Date')
                        ->required(),
                    Forms\Components\Textarea::make('reason')
                        ->required()
                        ->columnSpanFull(),
                ])->disabled(function ($get) {
                    return $get('record') && Auth::user()->hasAnyRole(['direksi', 'hrd', 'leader']);
                }),
        ];

        if (Auth::user()->hasAnyRole(['direksi', 'hrd', 'leader'])) {
            array_push($schema, Forms\Components\Section::make('Approval')
                ->headerActions([
                    SectionAction::make('reject')
                        ->label('Reject')
                        ->color('danger')
                        ->action(function (Cuti $record) {
                            $record->status = 'rejected';
                            $record->save();

                            return redirect()->route('filament.internal.resources.cutis.index');
                        }),
                ])
                ->schema([
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Toggle::make('approved_by_leader')
                                ->label('Leader')
                                ->onColor('success')
                                ->reactive()
                                ->disabled(fn() => !Auth::user()->hasRole('leader')), // Aktif jika user adalah Leader
                            Forms\Components\Toggle::make('approved_by_hrd')
                                ->label('HRD')
                                ->onColor('success')
                                ->helperText(
                                    'Approval dari Leader diperlukan sebelum disetujui oleh HRD.' // Tooltip
                                )
                                ->reactive()
                                ->disabled(fn($get) => !$get('approved_by_leader') || !Auth::user()->hasRole('hrd')), // Aktif jika Leader sudah approve dan user adalah Direksi

                            Forms\Components\Toggle::make('approved_by_direksi')
                                ->label('Direksi')
                                ->onColor('success')
                                ->helperText(
                                    'Approval dari HRD diperlukan sebelum disetujui oleh Direksi.' // Tooltip
                                )
                                ->reactive()
                                ->disabled(fn($get) => !$get('approved_by_hrd') || !Auth::user()->hasRole('direksi')) // Aktif jika Leader sudah approve dan user adalah Direksi
                        ])->columns(3),
                    Forms\Components\Textarea::make('notes')
                        ->label('Catatan')
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
                $is_acc = Auth::user()->hasAnyRole(['direksi', 'hrd', 'leader', 'super_admin']); //emang merah error tapi works
                if (!$is_acc) {
                    $query->where('user_id', Auth::user()->id);
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.profile.jabatan.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jumlah_hari')
                    ->numeric()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->alignCenter()
                    ->icons([
                        'heroicon-o-x-circle' => static fn($state): bool => $state === 'rejected',
                        'heroicon-o-clock' => static fn($state): bool => $state === 'pending',
                        'heroicon-m-check-badge' => static fn($state): bool => $state === 'approved',
                    ])
                    ->color(fn(string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'pending' => 'warning',
                    }),
                Tables\Columns\IconColumn::make('approved_by_leader')
                    ->label('Leader')
                    ->boolean()
                    ->alignCenter(),
                Tables\Columns\IconColumn::make('approved_by_hrd')
                    ->label('HRD')
                    ->alignCenter()
                    ->boolean(),
                Tables\Columns\IconColumn::make('approved_by_direksi')
                    ->label('Direksi')
                    ->alignCenter()
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
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('Jabatan')
                    ->relationship('user.profile.jabatan', 'name')
                    ->label('Jabatan')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->disabled(fn(Cuti $record): bool => $record->status === 'pending' || $record->status === 'rejected'),
                Action::make('cetak')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn(Cuti $record): string => route('cuti.export-pdf', $record))
                    ->openUrlInNewTab()
                    ->disabled(fn(Cuti $record): bool => $record->status === 'pending' || $record->status === 'rejected'),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                PdfViewerEntry::make('path_cuti_pdf')
                    ->label('')
                    ->minHeight('80svh')
                    ->columnSpanFull()
            ]);
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
