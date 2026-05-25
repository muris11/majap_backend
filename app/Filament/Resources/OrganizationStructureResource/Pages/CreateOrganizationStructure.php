<?php

namespace App\Filament\Resources\OrganizationStructureResource\Pages;

use App\Filament\Resources\OrganizationStructureResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrganizationStructure extends CreateRecord
{
    protected static string $resource = OrganizationStructureResource::class;

    public function getTitle(): string
    {
        return 'Tambah Struktur Organisasi';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
