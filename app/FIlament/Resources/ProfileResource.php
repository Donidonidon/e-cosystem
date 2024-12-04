<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Jabatan;
use App\Models\Profile;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\ImageEntry;
use Teguh02\IndonesiaTerritoryForms\Models\City;
use App\Filament\Resources\ProfileResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Teguh02\IndonesiaTerritoryForms\Models\District;
use Teguh02\IndonesiaTerritoryForms\Models\Province;

use Teguh02\IndonesiaTerritoryForms\Models\SubDistrict;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;
use App\Filament\Resources\ProfileResource\RelationManagers;
use Teguh02\IndonesiaTerritoryForms\IndonesiaTerritoryForms;

class ProfileResource extends Resource
{
    protected static ?string $model = Profile::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('first_name')
                    ->label('Nama Depan')
                    ->required(),
                TextInput::make('last_name')
                    ->label('Nama Belakang')
                    ->required(),
                TextInput::make('email')
                    ->suffixIcon('heroicon-s-envelope')
                    // ->email()
                    ->disabled()
                    ->default(Auth::user()->email)
                    ->dehydrated(fn($state) => Auth::user()->email),
                TextInput::make('nik')
                    ->label('NIK / Nomor Induk Kependudukan')
                    ->mask(RawJs::make(<<<'JS'
                        '9999 9999 9999 9999'
                    JS))
                    ->maxLength(19) // Panjang maksimal dengan spasi (16 angka + 3 spasi)
                    ->placeholder('0000 0000 0000 0000')
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('no_hp')
                    ->suffixIcon('heroicon-s-phone')
                    ->mask(RawJs::make(<<<'JS'
                        '6299999999999'
                    JS))
                    ->prefix('+62')
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

                Section::make('Alamat')
                    ->schema([
                        TextInput::make('alamat')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        IndonesiaTerritoryForms::province_form()
                            ->label('Provinsi')
                            ->required(),
                        // TextInput::make('coba')
                        //     ->default(fn() => collect(app(Province::class)->getProvinceNameById(11))->first()),
                        IndonesiaTerritoryForms::city_form()
                            ->label('Kota/Kabupaten')
                            ->required(),
                        IndonesiaTerritoryForms::district_form()
                            ->label('Kecamatan')
                            ->required(),
                        IndonesiaTerritoryForms::sub_district_form()
                            ->label('Kelurahan/Desa')
                            ->required(),
                    ]),

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
                Select::make('divisi_id')
                    ->relationship('divisi', 'name', fn($query) => $query->orderBy('id', 'asc'))
                    ->label('Divisi')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set) {
                        $set('jabatan_id', null);
                    }),

                Select::make('jabatan_id')
                    ->label('Jabatan')
                    ->options(function (callable $get) {
                        $divisiId = $get('divisi_id');
                        return $divisiId
                            ? Jabatan::where('divisi_id', $divisiId)
                            ->orderBy('id', 'asc')
                            ->pluck('name', 'id')
                            : [];
                    })
                    ->disabled(fn(callable $get) => !$get('divisi_id')) // Disable jika divisi belum dipilih
                    ->required()
                    ->reactive(),
                DatePicker::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->required()
                    ->maxDate(Date::now()),
                Select::make('kantor_id')
                    ->label('Kantor')
                    ->relationship('kantor', 'name')
                    ->required(),
                FileUpload::make('foto_ktp')
                    ->label('Foto KTP')
                    ->image()
                    ->maxSize(5120)
                    ->maxFiles(1)
                    ->directory('ktp')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                    ->columnSpanFull()
                    ->storeFileNamesIn(Auth::user()->name . '-Foto-Ktp')
                    ->required(),

                SignaturePad::make('signature')
                    ->label(__('Tanda Tangan'))
                    ->dotSize(2.0)
                    ->lineMinWidth(0.5)
                    ->lineMaxWidth(2.5)
                    ->throttle(16)
                    ->minDistance(5)
                    ->velocityFilterWeight(0.7)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $is_super_admin = Auth::user()->hasRole('super_admin'); //emang merah error tapi works
                if (!$is_super_admin) {
                    $query->where('user_id', Auth::user()->id);
                }
            })
            ->columns([
                TextColumn::make('first_name')
                    ->label('Nama Depan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email'),
                TextColumn::make('jabatan.name')
                    ->searchable(),
                TextColumn::make('tanggal_lahir')
                    ->sortable(),
                TextColumn::make('tanggal_masuk')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('jabatan_id')
                    ->relationship('jabatan', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
                Fieldset::make('Profile')
                    ->schema([
                        Infolists\Components\TextEntry::make('nik'),
                        Infolists\Components\TextEntry::make('first_name'),
                        Infolists\Components\TextEntry::make('last_name'),
                        Infolists\Components\TextEntry::make('email'),
                        Infolists\Components\TextEntry::make('no_hp'),
                        Infolists\Components\TextEntry::make('tempat_lahir'),
                        Infolists\Components\TextEntry::make('tanggal_lahir'),
                        Infolists\Components\TextEntry::make('jenis_kelamin'),
                        Infolists\Components\TextEntry::make('agama')
                    ])
                    ->columns([
                        'sm' => 3,
                        'xl' => 4,
                        '2xl' => 5,
                    ]),

                Fieldset::make('Alamat')
                    ->schema([
                        Infolists\Components\TextEntry::make('alamat')
                            ->columnSpan(2),
                        Infolists\Components\TextEntry::make('subdistrict_id')
                            ->formatStateUsing(fn($state) => collect(app(SubDistrict::class)->getSubdistrictNameById($state))->first()),
                        Infolists\Components\TextEntry::make('district_id')
                            ->formatStateUsing(fn($state) => collect(app(District::class)->getDistrictNameById($state))->first()),
                        Infolists\Components\TextEntry::make('city_id')
                            ->formatStateUsing(fn($state) => collect(app(City::class)->getCityNameById($state))->first()),
                        Infolists\Components\TextEntry::make('province_id')
                            ->formatStateUsing(fn($state) => collect(app(Province::class)->getProvinceNameById($state))->first()),
                    ])
                    ->columns([
                        'sm' => 2,
                        'xl' => 4,
                        '2xl' => 5,
                    ]),

                Fieldset::make('Profile Kantor')
                    ->schema([
                        Infolists\Components\TextEntry::make('divisi.name'),
                        Infolists\Components\TextEntry::make('jabatan.name'),
                        Infolists\Components\TextEntry::make('tanggal_masuk'),
                        Infolists\Components\TextEntry::make('kantor.name'),
                    ])
                    ->columns([
                        'sm' => 2,
                        'xl' => 4,
                        '2xl' => 5,
                    ]),

                Infolists\Components\Section::make('Media')
                    ->collapsible()
                    ->schema([
                        Infolists\Components\ImageEntry::make('foto_ktp'),
                        Infolists\Components\ImageEntry::make('signature'),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProfiles::route('/'),
            'create' => Pages\CreateProfile::route('/create'),
            'view' => Pages\ViewProfile::route('/{record}'),
            'edit' => Pages\EditProfile::route('/{record}/edit'),
        ];
    }
}
