<?php
 
 namespace App\Filament\Resources\DidYouKnowFactResource\Pages;
 
 use App\Filament\Resources\DidYouKnowFactResource;
 use Filament\Actions;
 use Filament\Resources\Pages\EditRecord;
 
 class EditDidYouKnowFact extends EditRecord
 {
     protected static string $resource = DidYouKnowFactResource::class;
 
     protected function getHeaderActions(): array
     {
         return [
             Actions\DeleteAction::make()
                 ->label('Hapus'),
         ];
     }
 
     protected function getRedirectUrl(): string
     {
         return $this->getResource()::getUrl('index');
     }
 }
