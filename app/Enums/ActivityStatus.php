<?php

namespace App\Enums;

enum ActivityStatus: string
 {
     case DRAFT = 'draft';
     case PUBLISHED = 'published';
     case ARCHIVED = 'archived';
 
     public function label(): string
     {
         return match ($this) {
             self::DRAFT => 'Draft',
             self::PUBLISHED => 'Diterbitkan',
             self::ARCHIVED => 'Diarsipkan',
         };
     }
 
     public function color(): string
     {
         return match ($this) {
             self::DRAFT => 'gray',
             self::PUBLISHED => 'success',
             self::ARCHIVED => 'warning',
         };
     }
 
     public static function toArray(): array
     {
         return array_map(fn ($case) => [
             'value' => $case->value,
             'label' => $case->label(),
             'color' => $case->color(),
         ], self::cases());
     }
 }
