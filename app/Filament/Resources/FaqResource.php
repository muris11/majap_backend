<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Actions;
use Filament\Forms\Components as FormComponents;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $recordTitleAttribute = 'question';

    public static function getGloballySearchableAttributes(): array
    {
        return ['question', 'answer'];
    }

    public static function getNavigationIcon(): string|null
    {
        return 'heroicon-o-question-mark-circle';
    }

    public static function getNavigationLabel(): string
    {
        return 'FAQ';
    }

    public static function getModelLabel(): string
    {
        return 'FAQ';
    }

    public static function getPluralModelLabel(): string
    {
        return 'FAQ';
    }

    public static function getNavigationSort(): ?int
    {
        return 6;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Konten';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaComponents\Section::make('Pertanyaan & Jawaban')
                    ->icon('heroicon-o-question-mark-circle')
                    ->description('Pertanyaan yang sering diajukan tentang MAJAP')
                    ->schema([
                        FormComponents\TextInput::make('question')
                            ->label('Pertanyaan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Bagaimana cara bergabung dengan MAJAP?'),
                        FormComponents\RichEditor::make('answer')
                            ->label('Jawaban')
                            ->required()
                            ->toolbarButtons([
                                'bold', 'italic', 'underline', 'link', 'orderedList', 'unorderedList',
                            ])
                            ->placeholder('Tulis jawaban di sini...'),
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
                            ->helperText('FAQ aktif akan ditampilkan di halaman FAQ website'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')
                    ->label('Pertanyaan')
                    ->searchable()
                    ->limit(50)
                    ->weight('semibold')
                    ->tooltip(fn (Faq $record) => $record->question),
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
                        ->label(fn (Faq $record) => $record->is_active ? 'Nonaktifkan' : 'Aktifkan')
                        ->icon('heroicon-o-power')
                        ->color(fn (Faq $record) => $record->is_active ? 'warning' : 'success')
                        ->action(fn (Faq $record) => $record->update(['is_active' => !$record->is_active])),
                    Actions\DeleteAction::make()
                        ->label('Hapus')
                        ->modalHeading('Hapus FAQ')
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
                        ->modalHeading('Hapus FAQ Terpilih')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
                ]),
            ])
            ->emptyStateHeading('Belum Ada FAQ')
            ->emptyStateDescription('Tambahkan pertanyaan yang sering diajukan.')
            ->emptyStateActions([
                Actions\CreateAction::make()
                    ->label('Tambah FAQ'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
