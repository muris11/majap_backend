<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DidYouKnowFactResource\Pages;
use App\Models\DidYouKnowFact;
use Filament\Actions;
use Filament\Forms\Components as FormComponents;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class DidYouKnowFactResource extends Resource
{
    protected static ?string $model = DidYouKnowFact::class;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGloballySearchableAttributes(): array
    {
        return ['title'];
    }

    public static function getNavigationIcon(): string|null
    {
        return 'heroicon-o-light-bulb';
    }

    public static function getNavigationLabel(): string
    {
        return 'Tahukah Kamu';
    }

    public static function getModelLabel(): string
    {
        return 'Fakta';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Tahukah Kamu';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Konten';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaComponents\Section::make('Isi Fakta')
                    ->icon('heroicon-o-light-bulb')
                    ->description('Fakta menarik yang akan ditampilkan di halaman depan website')
                    ->schema([
                        FormComponents\FileUpload::make('image')
                            ->label('Gambar')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('facts')
                            ->imageEditor()
                            ->openable()
                            ->downloadable()
                            ->previewable(true),
                        FormComponents\TextInput::make('title')
                            ->label('Fakta')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Polindra memiliki lebih dari 10.000 mahasiswa aktif!'),
                        FormComponents\TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Semakin kecil angka, semakin awal ditampilkan'),
                    ])
                    ->columns(2),

                SchemaComponents\Section::make('Pengaturan Tampilan')
                    ->icon('heroicon-o-eye')
                    ->schema([
                        FormComponents\Toggle::make('is_active')
                            ->label('Tampilkan')
                            ->default(true)
                            ->helperText('Fakta aktif akan ditampilkan secara acak di website'),
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
                    ->height(40)
                    ->square()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Fakta')
                    ->searchable()
                    ->limit(60)
                    ->weight('semibold')
                    ->tooltip(fn (DidYouKnowFact $record) => $record->title),
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
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->striped()
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
            ])
            ->actions([
                Actions\ActionGroup::make([
                    Actions\EditAction::make()->label('Ubah'),
                    Actions\Action::make('toggleActive')
                        ->label(fn (DidYouKnowFact $record) => $record->is_active ? 'Nonaktifkan' : 'Aktifkan')
                        ->icon('heroicon-o-power')
                        ->color(fn (DidYouKnowFact $record) => $record->is_active ? 'warning' : 'success')
                        ->action(fn (DidYouKnowFact $record) => $record->update(['is_active' => !$record->is_active])),
                    Actions\DeleteAction::make()
                        ->label('Hapus')
                        ->modalHeading('Hapus Fakta')
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
                        ->modalHeading('Hapus Fakta Terpilih')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
                ]),
            ])
            ->emptyStateHeading('Belum Ada Fakta')
            ->emptyStateDescription('Tambahkan fakta menarik tentang Polindra.')
            ->emptyStateActions([
                Actions\CreateAction::make()
                    ->label('Tambah Fakta'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDidYouKnowFacts::route('/'),
            'create' => Pages\CreateDidYouKnowFact::route('/create'),
            'edit' => Pages\EditDidYouKnowFact::route('/{record}/edit'),
        ];
    }
}
