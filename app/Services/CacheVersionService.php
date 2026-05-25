<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheVersionService
{
    private const VERSION_KEY = 'cache_versions';
    private const TTL = 86400; // 24 hours

    public function getVersion(string $resource): int
    {
        $versions = Cache::get(self::VERSION_KEY, []);
        return $versions[$resource] ?? 1;
    }

    public function getAllVersions(): array
    {
        return Cache::get(self::VERSION_KEY, [
            'settings' => 1,
            'hero_slides' => 1,
            'did_you_know' => 1,
            'timeline' => 1,
            'batches' => 1,
            'organization' => 1,
            'activities' => 1,
            'albums' => 1,
            'faqs' => 1,
            'schema' => 1,
        ]);
    }

    public function incrementVersion(string $resource): int
    {
        $versions = $this->getAllVersions();
        $versions[$resource] = ($versions[$resource] ?? 0) + 1;
        $versions['_updated_at'] = now()->toIso8601String();
        
        Cache::put(self::VERSION_KEY, $versions, self::TTL);
        
        // Also clear the resource cache
        $this->clearResourceCache($resource);
        
        return $versions[$resource];
    }

    public function incrementMultiple(array $resources): array
    {
        $versions = $this->getAllVersions();
        
        foreach ($resources as $resource) {
            $versions[$resource] = ($versions[$resource] ?? 0) + 1;
            $this->clearResourceCache($resource);
        }
        
        $versions['_updated_at'] = now()->toIso8601String();
        Cache::put(self::VERSION_KEY, $versions, self::TTL);
        
        return $versions;
    }

    protected function clearResourceCache(string $resource): void
    {
        $cacheKeys = [
            'settings' => ['settings'],
            'hero_slides' => ['hero_slides'],
            'did_you_know' => ['did_you_know_facts'],
            'timeline' => ['timeline'],
            'batches' => ['batches'],
            'organization' => ['organization_latest'],
            'activities' => [],
            'albums' => [],
            'faqs' => ['faqs'],
        ];

        foreach ($cacheKeys[$resource] ?? [] as $key) {
            Cache::forget($key);
        }
    }
}
