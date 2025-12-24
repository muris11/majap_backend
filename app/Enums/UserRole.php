<?php

namespace App\Enums;

enum UserRole: string
 {
     case ADMIN = 'admin';
     case EDITOR = 'editor';
     case VIEWER = 'viewer';
 
     public function label(): string
     {
         return match ($this) {
             self::ADMIN => 'Administrator',
             self::EDITOR => 'Editor',
             self::VIEWER => 'Viewer',
         };
     }
 
     public function color(): string
     {
         return match ($this) {
             self::ADMIN => 'danger',
             self::EDITOR => 'primary',
             self::VIEWER => 'gray',
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
