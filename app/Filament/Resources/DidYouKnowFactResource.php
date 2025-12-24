<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DidYouKnowFactResource\Pages;
use App\Models\DidYouKnowFact;
use Filament\Actions;
use Filament\Forms\Components as FormComponents;
use Filament\Resources\Resource;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class DidYouKnowFactResource extends Resource
{
    protected static ?string $model = DidYouKnowFact::class;

    public static function getNavigationIcon(): ?string
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
                SchemaComponents\Section::make('Detail Fakta')
                    ->description('Kelola gambar untuk section Tahukah Kamu')
                    ->schema([
                        FormComponents\TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Judul singkat untuk identifikasi'),
                        FormComponents\FileUpload::make('image')
                            ->label('Gambar')
                            ->image()
                            ->required()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('did-you-know')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->openable()
                            ->downloadable()
                            ->previewable(true)
                            ->columnSpanFull(),
                        FormComponents\TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->helperText('Urutan tampil (0 = pertama)'),
                        FormComponents\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->helperText('Tampilkan fakta ini di website'),
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
                    ->height(80)
                    ->width(80),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable(),
                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('order')
            ->reorderable('order')
            ->actions([
                Actions\EditAction::make()
                    ->label('Ubah'),
                Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Fakta')
                    ->modalDescription('Apakah Anda yakin ingin menghapus fakta ini?')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->modalCancelActionLabel('Batal'),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ])
            ->emptyStateHeading('Belum Ada Fakta')
            ->emptyStateDescription('Tambahkan gambar untuk section Tahukah Kamu.')
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
