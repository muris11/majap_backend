<?php

namespace App\Filament\Resources\ContactMessageResource\Pages;

use App\Filament\Resources\ContactMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContactMessages extends ListRecords
{
    protected static string $resource = ContactMessageResource::class;

    public function getTitle(): string
    {
        return 'Daftar Pesan';
    }

    protected function getHeaderActions(): array
    {
        return [
            // No create action - messages come from frontend
        ];
    }
}
