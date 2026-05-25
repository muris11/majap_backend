<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BatchResource\Pages;
use App\Models\Batch;
use Filament\Actions;
use Filament\Forms\Components as FormComponents;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class BatchResource extends Resource
{
    protected static ?string $model = Batch::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'description'];
    }

    public static function getNavigationIcon(): string|null
    {
        return 'heroicon-o-academic-cap';
    }

    public static function getNavigationLabel(): string
    {
        return 'Angkatan';
    }

    public static function getModelLabel(): string
    {
        return 'Angkatan';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Angkatan';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Data Master';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaComponents\Section::make('Detail Angkatan')
                    ->icon('heroicon-o-academic-cap')
                    ->description('Masukkan informasi angkatan mahasiswa')
                    ->schema([
                        FormComponents\TextInput::make('name')
                            ->label('Nama Angkatan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Angkatan 2024'),
                        FormComponents\TextInput::make('year')
                            ->label('Tahun')
                            ->required()
                            ->numeric()
                            ->minValue(2000)
                            ->maxValue(2100)
                            ->default(date('Y'))
                            ->placeholder('Contoh: 2024'),
                        FormComponents\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Deskripsi singkat tentang angkatan...'),
                        FormComponents\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->helperText('Angkatan aktif akan ditampilkan di website'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),
                Tables\Columns\TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable()
                    ->alignment('center'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('activities_count')
                    ->label('Kegiatan')
                    ->counts('activities')
                    ->alignment('center')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('albums_count')
                    ->label('Album')
                    ->counts('albums')
                    ->alignment('center')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('year', 'desc')
            ->striped()
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
            ])
            ->actions([
                Actions\ActionGroup::make([
                    Actions\EditAction::make()->label('Ubah'),
                    Actions\Action::make('toggleActive')
                        ->label(fn (Batch $record) => $record->is_active ? 'Nonaktifkan' : 'Aktifkan')
                        ->icon('heroicon-o-power')
                        ->color(fn (Batch $record) => $record->is_active ? 'warning' : 'success')
                        ->action(fn (Batch $record) => $record->update(['is_active' => !$record->is_active])),
                    Actions\DeleteAction::make()
                        ->label('Hapus')
                        ->modalHeading('Hapus Angkatan')
                        ->modalDescription('Semua data kegiatan dan album terkait mungkin terpengaruh.')
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
                        ->modalHeading('Hapus Angkatan Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus angkatan yang dipilih?')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
                ]),
            ])
            ->emptyStateHeading('Belum Ada Angkatan')
            ->emptyStateDescription('Mulai tambahkan data angkatan mahasiswa.')
            ->emptyStateActions([
                Actions\CreateAction::make()
                    ->label('Tambah Angkatan'),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBatches::route('/'),
            'create' => Pages\CreateBatch::route('/create'),
            'edit' => Pages\EditBatch::route('/{record}/edit'),
        ];
    }
}
