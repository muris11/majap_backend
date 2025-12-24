<?php

namespace App\Filament\Resources\OrganizationStructureResource\Pages;

use App\Filament\Resources\OrganizationStructureResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrganizationStructure extends CreateRecord
{
    protected static string $resource = OrganizationStructureResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
