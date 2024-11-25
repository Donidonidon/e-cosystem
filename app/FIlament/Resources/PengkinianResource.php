<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengkinianResource\Pages;
use App\Filament\Resources\PengkinianResource\RelationManagers;
use App\Models\Pengkinian;
use App\Models\Profile;
use DateTime;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class PengkinianResource extends Resource
{
    protected static ?string $model = Profile::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('last_name')
                    ->required(),
                TextInput::make('email')
                    ->email()
                    ->default(Auth::user()->email)
                    ->disabled(),
                TextInput::make('nik')
                    ->label('NIK / Nomor Induk Kependudukan')
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('no_hp')
                    ->label('Nomor Handphone')
                    ->tel()
                    ->required(),
                TextInput::make('tempat_lahir')
                    ->label('Tempat Lahir')
                    ->required(),
                DatePicker::make('tanggal_lahir')
                    ->label('Tanggal Lahir')
                    ->required()
                    ->maxDate(Date::now()),
                Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ])
                    ->required(),
                Select::make('agama')
                    ->label('Agama')
                    ->options([
                        'Islam' => 'Islam',
                        'Kristen' => 'Kristen',
                        'Katolik' => 'Katolik',
                        'Hindu' => 'Hindu',
                        'Buddha' => 'Buddha',
                        'Konghucu' => 'Konghucu',
                    ])
                    ->required(),
                TextInput::make('alamat')
                    ->required()
                    ->maxLength(255),
                Select::make('provinces_id')
                    ->label('Provinsi')
                    ->relationship('provinces', 'name')
                    ->required(),
                Select::make('regencies_id')
                    ->label('Kabupaten')
                    ->relationship('regencies', 'name')
                    ->required(),
                Select::make('districts_id')
                    ->label('Kecamatan')
                    ->relationship('districts', 'name')
                    ->required(),
                Select::make('ijasah_terakhir')
                    ->label('Ijasah Terakhir')
                    ->options([
                        'SD' => 'SD',
                        'SMP' => 'SMP',
                        'SMA' => 'SMA',
                        'S1' => 'S1',
                        'S2' => 'S2',
                    ])
                    ->required(),
                Select::make('jabatan_sekarang')
                    ->label('Jabatan Sekarang')
                    ->relationship('jabatan', 'name')
                    ->required(),
                DatePicker::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->required()
                    ->maxDate(Date::now()),
                TextInput::make('kantor')
                    ->label('Kantor')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListPengkinians::route('/'),
            'create' => Pages\CreatePengkinian::route('/create'),
            'edit' => Pages\EditPengkinian::route('/{record}/edit'),
        ];
    }
}
