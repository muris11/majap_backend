<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuggestionResource\Pages;
use App\Models\Suggestion;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SuggestionResource extends Resource
{
    protected static ?string $model = Suggestion::class;

    protected static ?string $recordTitleAttribute = 'content';

    public static function getGloballySearchableAttributes(): array
    {
        return ['content', 'category'];
    }

    public static function getNavigationIcon(): string|null
    {
        return 'heroicon-o-chat-bubble-bottom-center-text';
    }

    public static function getNavigationLabel(): string
    {
        return 'Saran & Masukan';
    }

    public static function getModelLabel(): string
    {
        return 'Saran';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Saran & Masukan';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Interaksi';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('content')
                    ->label('Isi Saran')
                    ->searchable()
                    ->limit(80)
                    ->tooltip(fn (Suggestion $record) => $record->content),
                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dikirim')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'organisasi' => 'Organisasi',
                        'kegiatan' => 'Kegiatan',
                        'website' => 'Website',
                        'lainnya' => 'Lainnya',
                    ]),
            ])
            ->actions([
                Actions\ActionGroup::make([
                    Actions\ViewAction::make()
                        ->label('Lihat')
                        ->modalHeading('Detail Saran')
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Tutup')
                        ->form([
                            \Filament\Forms\Components\TextInput::make('category')
                                ->label('Kategori'),
                            \Filament\Forms\Components\Textarea::make('content')
                                ->label('Isi Saran')
                                ->disabled()
                                ->rows(6),
                            \Filament\Forms\Components\TextInput::make('created_at')
                                ->label('Dikirim')
                                ->disabled(),
                        ])
                        ->fillForm(fn (Suggestion $record) => [
                            'category' => $record->category,
                            'content' => $record->content,
                            'created_at' => $record->created_at->format('d M Y H:i'),
                        ]),
                    Actions\DeleteAction::make()
                        ->label('Hapus')
                        ->modalHeading('Hapus Saran')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
                ]),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih')
                        ->modalHeading('Hapus Saran Terpilih')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
                ]),
            ])
            ->emptyStateHeading('Belum Ada Saran')
            ->emptyStateDescription('Belum ada saran & masukan dari pengunjung.')
            ->emptyStateIcon('heroicon-o-chat-bubble-bottom-center-text');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuggestions::route('/'),
        ];
    }
}
