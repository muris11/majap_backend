<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Actions;
use Filament\Forms\Components as FormComponents;
use Filament\Schemas\Components as SchemaComponents;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $recordTitleAttribute = 'subject';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'subject', 'message'];
    }

    public static function getNavigationIcon(): string|null
    {
        return 'heroicon-o-envelope';
    }

    public static function getNavigationLabel(): string
    {
        return 'Pesan Masuk';
    }

    public static function getModelLabel(): string
    {
        return 'Pesan';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Pesan Masuk';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Interaksi';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                SchemaComponents\Section::make('Detail Pengirim')
                    ->icon('heroicon-o-user')
                    ->schema([
                        FormComponents\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Nama pengirim'),
                        FormComponents\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Email pengirim'),
                        FormComponents\TextInput::make('phone')
                            ->label('No. Telepon')
                            ->tel()
                            ->maxLength(255)
                            ->placeholder('Nomor telepon (opsional)'),
                    ])
                    ->columns(2),

                SchemaComponents\Section::make('Pesan')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->schema([
                        FormComponents\TextInput::make('subject')
                            ->label('Subjek')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Judul pesan'),
                        FormComponents\Textarea::make('message')
                            ->label('Isi Pesan')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull()
                            ->placeholder('Isi pesan...'),
                    ]),

                SchemaComponents\Section::make('Status')
                    ->icon('heroicon-o-check-circle')
                    ->schema([
                        FormComponents\Toggle::make('is_read')
                            ->label('Sudah Dibaca')
                            ->default(false)
                            ->helperText('Tandai sebagai sudah dibaca'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->weight('semibold')
                    ->description(fn (ContactMessage $record) => $record->email),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Email disalin')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Subjek')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telepon')
                    ->toggleable()
                    ->placeholder('-'),
                Tables\Columns\IconColumn::make('is_read')
                    ->label('Dibaca')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diterima')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->since()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->filters([
                Tables\Filters\TernaryFilter::make('is_read')
                    ->label('Status Baca')
                    ->placeholder('Semua Pesan')
                    ->trueLabel('Sudah Dibaca')
                    ->falseLabel('Belum Dibaca'),
            ])
            ->actions([
                Actions\ActionGroup::make([
                    Actions\ViewAction::make()
                        ->label('Lihat')
                        ->modalHeading('Detail Pesan')
                        ->form([
                            FormComponents\TextInput::make('name')->label('Nama'),
                            FormComponents\TextInput::make('email')->label('Email'),
                            FormComponents\TextInput::make('phone')->label('Telepon'),
                            FormComponents\TextInput::make('subject')->label('Subjek'),
                            FormComponents\Textarea::make('message')->label('Pesan')->rows(5),
                            FormComponents\Toggle::make('is_read')->label('Sudah Dibaca'),
                        ])
                        ->action(fn (ContactMessage $record, array $data) => $record->update(['is_read' => $data['is_read'] ?? true])),
                    Actions\Action::make('toggleRead')
                        ->label(fn (ContactMessage $record) => $record->is_read ? 'Tandai Belum Dibaca' : 'Tandai Sudah Dibaca')
                        ->icon(fn (ContactMessage $record) => $record->is_read ? 'heroicon-o-envelope' : 'heroicon-o-eye')
                        ->color(fn (ContactMessage $record) => $record->is_read ? 'gray' : 'success')
                        ->action(fn (ContactMessage $record) => $record->update(['is_read' => !$record->is_read])),
                    Actions\DeleteAction::make()
                        ->label('Hapus')
                        ->modalHeading('Hapus Pesan')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
                ]),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\BulkAction::make('markAsRead')
                        ->label('Tandai Sudah Dibaca')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['is_read' => true]))),
                    Actions\BulkAction::make('markAsUnread')
                        ->label('Tandai Belum Dibaca')
                        ->icon('heroicon-o-envelope')
                        ->color('warning')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['is_read' => false]))),
                    Actions\DeleteBulkAction::make()
                        ->label('Hapus Terpilih')
                        ->modalHeading('Hapus Pesan Terpilih')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal'),
                ]),
            ])
            ->emptyStateHeading('Belum Ada Pesan')
            ->emptyStateDescription('Pesan dari pengunjung website akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-inbox');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'create' => Pages\CreateContactMessage::route('/create'),
            'edit' => Pages\EditContactMessage::route('/{record}/edit'),
        ];
    }
}
