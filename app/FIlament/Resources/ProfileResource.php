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
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Enums\FontWeight;
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
                Section::make('Informasi Pribadi')
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

                        FileUpload::make('profile_pic')
                            ->required()
                            ->label('Foto Profil')
                            ->image()
                            ->maxSize(5120)
                            ->maxFiles(1)
                            ->directory('profile')
                            ->uploadingMessage('Uploading attachment...')
                            ->columnSpanFull()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg']),
                    ])->columns(2),

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
                    ])->columns(2),

                Section::make('Profile Kantor')
                    ->schema([
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
                    ])->columns(2),

                Section::make('Media')
                    ->schema([
                        FileUpload::make('foto_ktp')
                            ->label('Foto KTP')
                            ->image()
                            ->maxSize(5120)
                            ->maxFiles(1)
                            ->directory('ktp')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg'])
                            ->storeFileNamesIn(Auth::user()->name . '-Foto-Ktp')
                            ->uploadingMessage('Uploading attachment...')
                            ->required(),
                        SignaturePad::make('signature')
                            ->label(__('Tanda Tangan'))
                            ->dotSize(2.0)
                            ->lineMinWidth(0.5)
                            ->lineMaxWidth(2.5)
                            ->throttle(16)
                            ->minDistance(5)
                            ->velocityFilterWeight(0.7)
                            ->required()
                            ->exportBackgroundColor('#fff')      // Pen color on dark mode (defaults to penColor)
                            ->exportPenColor('#333')
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create'),
                    ])->columns(2),
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
                ImageColumn::make('profile_pic')
                    ->label('Foto Profil')
                    ->circular(),
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
                Infolists\Components\Section::make('Profil Pribadi')
                    ->schema([
                        Infolists\Components\Split::make([
                            Infolists\Components\Section::make([
                                ImageEntry::make('profile_pic')
                                    ->label('Foto Profil'),
                            ])->grow(false),
                            Infolists\Components\Section::make([
                                TextEntry::make('nik')
                                    ->label('NIK / Nomor Induk Kependudukan')
                                    ->columnSpanFull(),
                                TextEntry::make('first_name')
                                    ->label('Nama Depan'),
                                TextEntry::make('last_name')
                                    ->label('Nama Belakang'),
                                TextEntry::make('email')
                                    ->label('Email'),
                                TextEntry::make('no_hp')
                                    ->label('Nomor Handphone'),
                                TextEntry::make('tempat_lahir')
                                    ->label('Tempat Lahir'),
                                TextEntry::make('tanggal_lahir')
                                    ->label('Tanggal Lahir'),
                                TextEntry::make('jenis_kelamin')
                                    ->label('Jenis Kelamin'),
                                TextEntry::make('agama')
                                    ->label('Agama'),
                            ])->columns(2),
                        ])->from('md'),
                    ]),

                Infolists\Components\Section::make('Alamat')
                    ->schema([
                        Infolists\Components\TextEntry::make('alamat')
                            ->label('Alamat Lengkap')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('subdistrict_id')
                            ->label('Desa')
                            ->formatStateUsing(fn($state) => collect(app(SubDistrict::class)->getSubdistrictNameById($state))->first()),
                        Infolists\Components\TextEntry::make('district_id')
                            ->label('Kecamatan / Kelurahan')
                            ->formatStateUsing(fn($state) => collect(app(District::class)->getDistrictNameById($state))->first()),
                        Infolists\Components\TextEntry::make('city_id')
                            ->label('Kota/Kabupaten')
                            ->formatStateUsing(fn($state) => collect(app(City::class)->getCityNameById($state))->first()),
                        Infolists\Components\TextEntry::make('province_id')
                            ->label('Provinsi')
                            ->formatStateUsing(fn($state) => collect(app(Province::class)->getProvinceNameById($state))->first()),
                    ])
                    ->columns([
                        'sm' => 2,
                        'xl' => 4,
                        '2xl' => 4,
                    ]),

                Infolists\Components\Section::make('Profile Kantor')
                    ->schema([
                        Infolists\Components\TextEntry::make('divisi.name'),
                        Infolists\Components\TextEntry::make('jabatan.name'),
                        Infolists\Components\TextEntry::make('tanggal_masuk'),
                        Infolists\Components\TextEntry::make('kantor.name'),
                    ])
                    ->columns([
                        'sm' => 2,
                        'xl' => 4,
                        '2xl' => 4,
                    ]),

                Infolists\Components\Section::make('Media')
                    ->collapsible()
                    ->schema([
                        Infolists\Components\ImageEntry::make('foto_ktp'),
                        Infolists\Components\ImageEntry::make('signature')
                            ->label('Tanda Tangan'),
                    ])->columns(2),
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
