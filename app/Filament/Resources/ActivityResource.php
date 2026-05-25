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

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'short_description', 'location', 'batch.name'];
    }

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
                    ->icon('heroicon-o-information-circle')
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
                    ->icon('heroicon-o-photo')
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
                    ->icon('heroicon-o-document-text')
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
                    ->icon('heroicon-o-cog-6-tooth')
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
                    ->square()
                    ->height(48),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(40)
                    ->weight('semibold'),
                Tables\Columns\TextColumn::make('batch.name')
                    ->label('Angkatan')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('event_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->limit(20)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Publikasi')
                    ->boolean()
                    ->toggleable(),
            ])
            ->defaultSort('event_date', 'desc')
            ->striped()
            ->filters([
                Tables\Filters\SelectFilter::make('batch')
                    ->relationship('batch', 'name')
                    ->label('Angkatan')
                    ->placeholder('Semua Angkatan')
                    ->searchable()
                    ->preload(),
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
                Actions\ActionGroup::make([
                    Actions\EditAction::make()->label('Ubah'),
                    Actions\Action::make('togglePublished')
                        ->label(fn (Activity $record) => $record->is_published ? 'Tarik Publikasi' : 'Publikasikan')
                        ->icon(fn (Activity $record) => $record->is_published ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                        ->color(fn (Activity $record) => $record->is_published ? 'warning' : 'success')
                        ->action(fn (Activity $record) => $record->update(['is_published' => !$record->is_published])),
                    Actions\Action::make('toggleFeatured')
                        ->label(fn (Activity $record) => $record->is_featured ? 'Hapus Unggulan' : 'Jadikan Unggulan')
                        ->icon(fn (Activity $record) => $record->is_featured ? 'heroicon-o-star' : 'heroicon-o-star')
                        ->color(fn (Activity $record) => $record->is_featured ? 'gray' : 'warning')
                        ->action(fn (Activity $record) => $record->update(['is_featured' => !$record->is_featured])),
                    Actions\DeleteAction::make()
                        ->label('Hapus')
                        ->modalHeading('Hapus Kegiatan')
                        ->modalDescription('Apakah Anda yakin ingin menghapus kegiatan ini? Tindakan ini tidak dapat dibatalkan.')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
                ]),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\BulkAction::make('togglePublished')
                        ->label('Publikasikan/Tarik')
                        ->icon('heroicon-o-eye')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['is_published' => true]))),
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
