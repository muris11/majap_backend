<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationStructureResource\Pages;
use App\Models\OrganizationStructure;
use Filament\Actions;
use Filament\Forms\Components as FormComponents;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class OrganizationStructureResource extends Resource
{
    protected static ?string $model = OrganizationStructure::class;

    public static function getNavigationIcon(): string|null
    {
        return 'heroicon-o-user-group';
    }

    public static function getNavigationLabel(): string
    {
        return 'Struktur Organisasi';
    }

    public static function getModelLabel(): string
    {
        return 'Struktur';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Struktur Organisasi';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Data Master';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaComponents\Section::make('Detail Anggota')
                    ->description('Masukkan informasi anggota struktur organisasi')
                    ->schema([
                        FormComponents\TextInput::make('name')
                            ->label('Nama Anggota')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Budi Santoso'),
                        FormComponents\TextInput::make('position')
                            ->label('Jabatan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Ketua'),
                        FormComponents\FileUpload::make('photo')
                            ->label('Foto')
                            ->directory('organization-structures')
                            ->image()
                            ->maxSize(2048),
                        FormComponents\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(2)
                            ->columnSpanFull()
                            ->placeholder('Deskripsi tugas atau keterangan tambahan...'),
                        FormComponents\TextInput::make('level')
                            ->label('Level Hierarki')
                            ->numeric()
                            ->default(0)
                            ->helperText('0 = Pimpinan, 1 = Wakil, 2 = Divisi, dst'),
                        FormComponents\TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->helperText('Urutan tampil dalam level yang sama'),
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('position')
                    ->label('Jabatan')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto')
                    ->circular(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50),
                Tables\Columns\TextColumn::make('level')
                    ->label('Level')
                    ->sortable(),
                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('level')
            ->reorderable('order')
            ->filters([])
            ->actions([
                Actions\EditAction::make()
                    ->label('Ubah'),
                Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->modalHeading('Hapus Anggota')
                    ->modalDescription('Apakah Anda yakin ingin menghapus anggota ini dari struktur organisasi?')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->modalCancelActionLabel('Batal'),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih')
                        ->modalHeading('Hapus Anggota Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus anggota yang dipilih?')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
                ]),
            ])
            ->emptyStateHeading('Belum Ada Struktur Organisasi')
            ->emptyStateDescription('Mulai tambahkan anggota struktur organisasi.')
            ->emptyStateActions([
                Actions\CreateAction::make()
                    ->label('Tambah Anggota'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganizationStructures::route('/'),
            'create' => Pages\CreateOrganizationStructure::route('/create'),
            'edit' => Pages\EditOrganizationStructure::route('/{record}/edit'),
        ];
    }
}
