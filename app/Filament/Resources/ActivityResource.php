<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use App\Models\Activity;
use Filament\Actions;
use Filament\Forms\Components as FormComponents;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    public static function getNavigationIcon(): string|null
    {
        return 'heroicon-o-calendar-days';
    }

    public static function getNavigationLabel(): string
    {
        return 'Kegiatan';
    }

    public static function getModelLabel(): string
    {
        return 'Kegiatan';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Kegiatan';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Konten';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaComponents\Section::make('Info Kegiatan')
                    ->schema([
                        FormComponents\Select::make('batch_id')
                            ->label('Angkatan')
                            ->relationship('batch', 'name')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->placeholder('Pilih Angkatan'),
                        FormComponents\TextInput::make('title')
                            ->label('Judul Kegiatan')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state)))
                            ->placeholder('Masukkan judul kegiatan'),
                        FormComponents\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('slug-otomatis-dari-judul'),
                        FormComponents\DatePicker::make('event_date')
                            ->label('Tanggal Kegiatan')
                            ->required()
                            ->placeholder('Pilih tanggal'),
                        FormComponents\TextInput::make('location')
                            ->label('Lokasi')
                            ->maxLength(255)
                            ->placeholder('Contoh: Aula Kampus'),
                        FormComponents\Textarea::make('short_description')
                            ->label('Deskripsi Singkat')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull()
                            ->helperText('Ringkasan singkat untuk ditampilkan pada kartu kegiatan')
                            ->placeholder('Tulis deskripsi singkat kegiatan...'),
                    ])
                    ->columns(2),

                SchemaComponents\Section::make('Gambar Cover')
                    ->schema([
                        FormComponents\FileUpload::make('cover_image')
                            ->label('Gambar Cover')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('activities')
                            ->imageEditor()
                            ->openable()
                            ->downloadable()
                            ->previewable(true)
                            ->columnSpanFull(),
                    ]),

                SchemaComponents\Section::make('Konten Lengkap')
                    ->schema([
                        FormComponents\RichEditor::make('content')
                            ->label('Narasi Kegiatan')
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'link',
                                'h2',
                                'h3',
                                'blockquote',
                                'bulletList',
                                'orderedList',
                                'codeBlock',
                                'attachFiles',
                                'undo',
                                'redo',
                            ])
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('activities/content')
                            ->columnSpanFull(),
                    ]),

                SchemaComponents\Section::make('Pengaturan')
                    ->schema([
                        FormComponents\Toggle::make('is_featured')
                            ->label('Tampilkan di Beranda')
                            ->default(false),
                        FormComponents\Toggle::make('is_published')
                            ->label('Publikasikan')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->disk('public')
                    ->square(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('batch.name')
                    ->label('Angkatan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('event_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Dipublikasikan')
                    ->boolean(),
            ])
            ->defaultSort('event_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('batch')
                    ->relationship('batch', 'name')
                    ->label('Angkatan')
                    ->placeholder('Semua Angkatan'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Unggulan')
                    ->placeholder('Semua')
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak'),
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Dipublikasikan')
                    ->placeholder('Semua')
                    ->trueLabel('Ya')
                    ->falseLabel('Tidak'),
            ])
            ->actions([
                Actions\EditAction::make()
                    ->label('Ubah'),
                Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Kegiatan')
                    ->modalDescription('Apakah Anda yakin ingin menghapus kegiatan ini? Tindakan ini tidak dapat dibatalkan.')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->modalCancelActionLabel('Batal'),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih')
                        ->modalHeading('Hapus Kegiatan Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus kegiatan yang dipilih? Tindakan ini tidak dapat dibatalkan.')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
                ]),
            ])
            ->emptyStateHeading('Belum Ada Kegiatan')
            ->emptyStateDescription('Mulai tambahkan kegiatan baru dengan mengklik tombol di bawah.')
            ->emptyStateActions([
                Actions\CreateAction::make()
                    ->label('Tambah Kegiatan'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivity::route('/create'),
            'edit' => Pages\EditActivity::route('/{record}/edit'),
        ];
    }
}