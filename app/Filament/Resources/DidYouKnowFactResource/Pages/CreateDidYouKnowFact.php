<?php
 
 namespace App\Filament\Resources\DidYouKnowFactResource\Pages;
 
 use App\Filament\Resources\DidYouKnowFactResource;
 use Filament\Resources\Pages\CreateRecord;
 
 class CreateDidYouKnowFact extends CreateRecord
 {
     protected static string $resource = DidYouKnowFactResource::class;
 
     protected function getRedirectUrl(): string
     {
         return $this->getResource()::getUrl('index');
     }
 }
