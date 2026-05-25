<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlbumResource\Pages;
use App\Models\Album;
use Filament\Actions;
use Filament\Forms\Components as FormComponents;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AlbumResource extends Resource
{
    protected static ?string $model = Album::class;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'description', 'batch.name', 'activity.title'];
    }

    public static function getNavigationIcon(): string|null
    {
        return 'heroicon-o-photo';
    }

    public static function getNavigationLabel(): string
    {
        return 'Album';
    }

    public static function getModelLabel(): string
    {
        return 'Album';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Album';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Konten';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaComponents\Section::make('Info Album')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        FormComponents\Select::make('batch_id')
                            ->label('Angkatan')
                            ->relationship('batch', 'name')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->placeholder('Pilih Angkatan'),
                        FormComponents\Select::make('activity_id')
                            ->label('Terkait Kegiatan')
                            ->relationship('activity', 'title')
                            ->searchable()
                            ->preload()
                            ->helperText('Opsional - Hubungkan album dengan kegiatan')
                            ->placeholder('Pilih Kegiatan (Opsional)'),
                        FormComponents\TextInput::make('title')
                            ->label('Judul Album')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state)))
                            ->placeholder('Masukkan judul album'),
                        FormComponents\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('slug-otomatis-dari-judul'),
                        FormComponents\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Tulis deskripsi album...'),
                    ])
                    ->columns(2),

                SchemaComponents\Section::make('Cover & Publikasi')
                    ->icon('heroicon-o-camera')
                    ->schema([
                        FormComponents\FileUpload::make('cover_image')
                            ->label('Gambar Cover')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('albums')
                            ->imageEditor()
                            ->openable()
                            ->downloadable()
                            ->previewable(true),
                        FormComponents\Toggle::make('is_published')
                            ->label('Publikasikan')
                            ->default(true),
                    ]),

                SchemaComponents\Section::make('Foto-foto')
                    ->icon('heroicon-o-photo')
                    ->description('Kelola foto-foto dalam album ini')
                    ->schema([
                        FormComponents\Repeater::make('photos')
                            ->relationship()
                            ->label('Daftar Foto')
                            ->schema([
                                FormComponents\FileUpload::make('image_path')
                                    ->label('Foto')
                                    ->image()
                                    ->required()
                                    ->disk('public')
                                    ->visibility('public')
                                    ->directory('albums/photos')
                                    ->openable()
                                    ->downloadable()
                                    ->previewable(true),
                                FormComponents\TextInput::make('caption')
                                    ->label('Caption')
                                    ->maxLength(255)
                                    ->placeholder('Keterangan foto'),
                                FormComponents\TextInput::make('order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columns(3)
                            ->collapsible()
                            ->reorderableWithButtons()
                            ->orderColumn('order')
                            ->addActionLabel('Tambah Foto')
                            ->itemLabel(fn (array $state): ?string => $state['caption'] ?? 'Foto Baru'),
                    ]),
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
                Tables\Columns\TextColumn::make('activity.title')
                    ->label('Kegiatan')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('photos_count')
                    ->label('Foto')
                    ->counts('photos')
                    ->alignment('center'),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Publikasi')
                    ->boolean()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->filters([
                Tables\Filters\SelectFilter::make('batch')
                    ->relationship('batch', 'name')
                    ->label('Angkatan')
                    ->placeholder('Semua Angkatan')
                    ->searchable()
                    ->preload(),
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
                        ->label(fn (Album $record) => $record->is_published ? 'Tarik Publikasi' : 'Publikasikan')
                        ->icon('heroicon-o-eye')
                        ->color(fn (Album $record) => $record->is_published ? 'warning' : 'success')
                        ->action(fn (Album $record) => $record->update(['is_published' => !$record->is_published])),
                    Actions\DeleteAction::make()
                        ->label('Hapus')
                        ->modalHeading('Hapus Album')
                        ->modalDescription('Apakah Anda yakin ingin menghapus album ini beserta semua fotonya? Tindakan ini tidak dapat dibatalkan.')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
                ]),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\BulkAction::make('togglePublished')
                        ->label('Publikasikan')
                        ->icon('heroicon-o-eye')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['is_published' => true]))),
                    Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih')
                        ->modalHeading('Hapus Album Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus album yang dipilih? Tindakan ini tidak dapat dibatalkan.')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
                ]),
            ])
            ->emptyStateHeading('Belum Ada Album')
            ->emptyStateDescription('Mulai tambahkan album baru dengan mengklik tombol di bawah.')
            ->emptyStateActions([
                Actions\CreateAction::make()
                    ->label('Tambah Album'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAlbums::route('/'),
            'create' => Pages\CreateAlbum::route('/create'),
            'edit' => Pages\EditAlbum::route('/{record}/edit'),
        ];
    }
}
