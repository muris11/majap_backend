<?php
 
 namespace App\Filament\Resources\DidYouKnowFactResource\Pages;
 
 use App\Filament\Resources\DidYouKnowFactResource;
 use Filament\Actions;
 use Filament\Resources\Pages\ListRecords;
 
 class ListDidYouKnowFacts extends ListRecords
 {
     protected static string $resource = DidYouKnowFactResource::class;
 
     protected function getHeaderActions(): array
     {
         return [
             Actions\CreateAction::make()
                 ->label('Tambah Fakta'),
         ];
     }
 }
