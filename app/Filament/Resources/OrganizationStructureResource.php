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

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'position', 'description'];
    }

    public static function getNavigationIcon(): string|null
    {
        return 'heroicon-o-rectangle-group';
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
        return 4;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Konten';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaComponents\Section::make('Detail Anggota')
                    ->icon('heroicon-o-user')
                    ->schema([
                        FormComponents\Select::make('batch_id')
                            ->label('Angkatan')
                            ->relationship('batch', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih Angkatan'),
                        FormComponents\TextInput::make('position')
                            ->label('Jabatan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Ketua'),
                        FormComponents\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Nama lengkap'),
                        FormComponents\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Deskripsi anggota...'),
                    ])
                    ->columns(2),

                SchemaComponents\Section::make('Foto')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        FormComponents\FileUpload::make('photo')
                            ->label('Foto')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('organization-structures')
                            ->imageEditor()
                            ->openable()
                            ->downloadable()
                            ->previewable(true)
                            ->columnSpanFull(),
                    ]),

                SchemaComponents\Section::make('Urutan & Level')
                    ->icon('heroicon-o-list-bullet')
                    ->schema([
                        FormComponents\TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->helperText('Urutan dalam level yang sama'),
                        FormComponents\TextInput::make('level')
                            ->label('Level')
                            ->numeric()
                            ->default(0)
                            ->helperText('0=Pembina, 1=Ketua, 2=Wakil, 3=Anggota'),
                    ])
                    ->columns(2),

                SchemaComponents\Section::make('Pengaturan')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        FormComponents\Toggle::make('is_active')
                            ->label('Tampilkan')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto')
                    ->disk('public')
                    ->circular()
                    ->height(40),
                Tables\Columns\TextColumn::make('position')
                    ->label('Jabatan')
                    ->searchable()
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->weight('semibold'),
                Tables\Columns\TextColumn::make('batch.name')
                    ->label('Angkatan')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('level')
                    ->label('Level')
                    ->sortable()
                    ->alignment('center')
                    ->toggleable(),
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
            ->defaultSort('level', 'asc')
            ->reorderable('order')
            ->striped()
            ->filters([
                Tables\Filters\SelectFilter::make('batch')
                    ->relationship('batch', 'name')
                    ->label('Angkatan')
                    ->placeholder('Semua')
                    ->searchable()
                    ->preload(),
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
                        ->label(fn (OrganizationStructure $record) => $record->is_active ? 'Nonaktifkan' : 'Aktifkan')
                        ->icon('heroicon-o-power')
                        ->color(fn (OrganizationStructure $record) => $record->is_active ? 'warning' : 'success')
                        ->action(fn (OrganizationStructure $record) => $record->update(['is_active' => !$record->is_active])),
                    Actions\DeleteAction::make()
                        ->label('Hapus')
                        ->modalHeading('Hapus Anggota')
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
                        ->modalHeading('Hapus Anggota Terpilih')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
                ]),
            ])
            ->emptyStateHeading('Belum Ada Struktur')
            ->emptyStateDescription('Tambahkan anggota struktur organisasi angkatan.')
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
