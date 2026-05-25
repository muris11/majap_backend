<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimelineEventResource\Pages;
use App\Models\TimelineEvent;
use Filament\Actions;
use Filament\Forms\Components as FormComponents;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class TimelineEventResource extends Resource
{
    protected static ?string $model = TimelineEvent::class;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'description', 'location', 'batch.name'];
    }

    public static function getNavigationIcon(): string|null
    {
        return 'heroicon-o-clock';
    }

    public static function getNavigationLabel(): string
    {
        return 'Linimasa';
    }

    public static function getModelLabel(): string
    {
        return 'Event';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Linimasa';
    }

    public static function getNavigationSort(): ?int
    {
        return 5;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Konten';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaComponents\Section::make('Detail Event')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        FormComponents\Select::make('batch_id')
                            ->label('Angkatan')
                            ->relationship('batch', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih Angkatan'),
                        FormComponents\TextInput::make('title')
                            ->label('Judul Event')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Dies Natalis ke-10'),
                        FormComponents\DatePicker::make('event_date')
                            ->label('Tanggal')
                            ->required()
                            ->placeholder('Pilih tanggal'),
                        FormComponents\TextInput::make('location')
                            ->label('Lokasi')
                            ->maxLength(255)
                            ->placeholder('Lokasi event'),
                    ])
                    ->columns(2),

                SchemaComponents\Section::make('Konten')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        FormComponents\Textarea::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull()
                            ->placeholder('Ceritakan tentang event ini...'),
                        FormComponents\FileUpload::make('image')
                            ->label('Gambar')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('timeline-events')
                            ->imageEditor()
                            ->openable()
                            ->downloadable()
                            ->previewable(true),
                    ]),

                SchemaComponents\Section::make('Pengaturan')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        FormComponents\Toggle::make('is_published')
                            ->label('Publikasikan')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->disk('public')
                    ->square()
                    ->height(40)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(40)
                    ->weight('semibold'),
                Tables\Columns\TextColumn::make('event_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('batch.name')
                    ->label('Angkatan')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->limit(20)
                    ->toggleable()
                    ->placeholder('-'),
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
                    ->placeholder('Semua')
                    ->searchable()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Status Publikasi')
                    ->placeholder('Semua')
                    ->trueLabel('Dipublikasikan')
                    ->falseLabel('Tidak Dipublikasikan'),
            ])
            ->actions([
                Actions\ActionGroup::make([
                    Actions\EditAction::make()->label('Ubah'),
                    Actions\Action::make('togglePublished')
                        ->label(fn (TimelineEvent $record) => $record->is_published ? 'Tarik Publikasi' : 'Publikasikan')
                        ->icon('heroicon-o-eye')
                        ->color(fn (TimelineEvent $record) => $record->is_published ? 'warning' : 'success')
                        ->action(fn (TimelineEvent $record) => $record->update(['is_published' => !$record->is_published])),
                    Actions\ViewAction::make()
                        ->label('Lihat')
                        ->modalHeading('Detail Event'),
                    Actions\DeleteAction::make()
                        ->label('Hapus')
                        ->modalHeading('Hapus Event')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
                ]),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\BulkAction::make('togglePublished')
                        ->label('Publikasikan')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['is_published' => true]))),
                    Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih')
                        ->modalHeading('Hapus Event Terpilih')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
                ]),
            ])
            ->emptyStateHeading('Belum Ada Event')
            ->emptyStateDescription('Tambahkan event untuk linimasa angkatan.')
            ->emptyStateActions([
                Actions\CreateAction::make()
                    ->label('Tambah Event'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimelineEvents::route('/'),
            'create' => Pages\CreateTimelineEvent::route('/create'),
            'edit' => Pages\EditTimelineEvent::route('/{record}/edit'),
        ];
    }
}
