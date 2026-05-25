<?php

namespace App\Filament\Resources\SuggestionResource\Pages;

use App\Filament\Resources\SuggestionResource;
use Filament\Resources\Pages\ListRecords;

class ListSuggestions extends ListRecords
{
    protected static string $resource = SuggestionResource::class;

    public function getTitle(): string
    {
        return 'Daftar Saran & Masukan';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
