<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroSlideResource\Pages;
use App\Models\HeroSlide;
use Filament\Actions;
use Filament\Forms\Components as FormComponents;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class HeroSlideResource extends Resource
{
    protected static ?string $model = HeroSlide::class;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'subtitle'];
    }

    public static function getNavigationIcon(): string|null
    {
        return 'heroicon-o-window';
    }

    public static function getNavigationLabel(): string
    {
        return 'Slide Hero';
    }

    public static function getModelLabel(): string
    {
        return 'Slide';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Slide Hero';
    }

    public static function getNavigationSort(): ?int
    {
        return -1;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Beranda';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaComponents\Section::make('Konten Slide')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        FormComponents\FileUpload::make('image')
                            ->label('Gambar Latar')
                            ->image()
                            ->required()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('hero-slides')
                            ->imageEditor()
                            ->openable()
                            ->downloadable()
                            ->previewable(true),
                        FormComponents\TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Judul utama slide'),
                        FormComponents\TextInput::make('subtitle')
                            ->label('Subjudul')
                            ->maxLength(255)
                            ->placeholder('Teks pendukung judul'),
                    ])
                    ->columns(2),

                SchemaComponents\Section::make('Pengaturan')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        FormComponents\TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Semakin kecil angka, semakin awal ditampilkan'),
                        FormComponents\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->disk('public')
                    ->height(48)
                    ->width(80)
                    ->extraImgAttributes(['class' => 'object-cover rounded']),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(30)
                    ->weight('semibold'),
                Tables\Columns\TextColumn::make('subtitle')
                    ->label('Subjudul')
                    ->limit(30)
                    ->toggleable()
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable()
                    ->alignment('center')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->striped()
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
            ])
            ->actions([
                Actions\ActionGroup::make([
                    Actions\EditAction::make()->label('Ubah'),
                    Actions\Action::make('toggleActive')
                        ->label(fn (HeroSlide $record) => $record->is_active ? 'Nonaktifkan' : 'Aktifkan')
                        ->icon('heroicon-o-power')
                        ->color(fn (HeroSlide $record) => $record->is_active ? 'warning' : 'success')
                        ->action(fn (HeroSlide $record) => $record->update(['is_active' => !$record->is_active])),
                    Actions\DeleteAction::make()
                        ->label('Hapus')
                        ->modalHeading('Hapus Slide')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
                ]),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\BulkAction::make('toggleActive')
                        ->label('Aktifkan')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['is_active' => true]))),
                    Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih')
                        ->modalHeading('Hapus Slide Terpilih')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
                ]),
            ])
            ->emptyStateHeading('Belum Ada Slide')
            ->emptyStateDescription('Tambahkan slide untuk halaman depan website.')
            ->emptyStateActions([
                Actions\CreateAction::make()
                    ->label('Tambah Slide'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHeroSlides::route('/'),
            'create' => Pages\CreateHeroSlide::route('/create'),
            'edit' => Pages\EditHeroSlide::route('/{record}/edit'),
        ];
    }
}
