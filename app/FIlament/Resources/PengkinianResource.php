<?php

namespace App\Filament\Resources;

use DateTime;
use Filament\Forms;
use Filament\Tables;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Profile;
use Filament\Forms\Form;
use App\Models\Pengkinian;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Faker\Provider\ar_EG\Text;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput\Mask;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PengkinianResource\Pages;
use Teguh02\IndonesiaTerritoryForms\Models\Province;
use Teguh02\IndonesiaTerritoryForms\IndonesiaTerritoryForms;
use App\Filament\Resources\PengkinianResource\RelationManagers;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PengkinianResource extends Resource
{
    protected static ?string $model = Profile::class;

    protected static ?string $slug = 'pengkinian';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('users_id')
                    ->default(Auth::user()->id)
                    ->disabled()
                    ->dehydrated(fn($state) => Auth::user()->id),
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
                Select::make('divisi')
                    ->relationship('divisi', 'name', fn($query) => $query->orderBy('id', 'asc'))
                    ->label('Divisi')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set) {
                        // Kosongkan jabatan_id jika divisi diganti
                        $set('jabatan_sekarang', null);
                    }), // Aktifkan reaktivitas agar pilihan jabatan berubah sesuai divisi

                Select::make('jabatan_sekarang')
                    ->label('Jabatan')
                    ->options(function (callable $get) {
                        $divisiId = $get('divisi');
                        return $divisiId
                            ? Jabatan::where('divisi_id', $divisiId)
                            ->orderBy('id', 'asc')
                            ->pluck('jabatan', 'id')
                            : [];
                    })
                    ->disabled(fn(callable $get) => !$get('divisi')) // Disable jika divisi belum dipilih
                    ->required()
                    ->reactive(),
                DatePicker::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->required()
                    ->maxDate(Date::now()),
                Select::make('kantor')
                    ->label('Kantor')
                    ->relationship('kantor', 'nama_kantor')
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
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')
                    ->label('Nama Depan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email'),
                TextColumn::make('jabatan.jabatan')
                    ->sortable(),
                TextColumn::make('tanggal_lahir')
                    ->sortable(),
                TextColumn::make('tanggal_masuk')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('jabatan')
                    ->relationship('jabatan', 'jabatan'),
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
