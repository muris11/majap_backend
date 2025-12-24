 <?php
 
 namespace App\Services\Schema;
 
 use App\Enums\ActivityStatus;
 use App\Enums\AlbumStatus;
 use App\Enums\UserRole;
 
 class EnumRegistry
 {
     protected static array $registry = [
         'activity_status' => ActivityStatus::class,
         'album_status' => AlbumStatus::class,
         'user_role' => UserRole::class,
     ];
 
     public static function get(string $key): ?array
     {
         $enumClass = self::$registry[$key] ?? null;
 
         if (!$enumClass || !enum_exists($enumClass)) {
             return null;
         }
 
         return $enumClass::toArray();
     }
 
     public static function all(): array
     {
         $result = [];
 
         foreach (self::$registry as $key => $enumClass) {
             if (enum_exists($enumClass)) {
                 $result[$key] = $enumClass::toArray();
             }
         }
 
         return $result;
     }
 
     public static function keys(): array
     {
         return array_keys(self::$registry);
     }
 
     public static function register(string $key, string $enumClass): void
     {
         self::$registry[$key] = $enumClass;
     }
 
     public static function getMultiple(array $keys): array
     {
         $result = [];
 
         foreach ($keys as $key) {
             $enum = self::get($key);
             if ($enum !== null) {
                 $result[$key] = $enum;
             }
         }
 
         return $result;
     }
 }
