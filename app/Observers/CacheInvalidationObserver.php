<?php

namespace App\Observers;

use App\Services\CacheVersionService;
use Illuminate\Database\Eloquent\Model;

class CacheInvalidationObserver
{
    protected static array $modelResourceMap = [
        \App\Models\Setting::class => 'settings',
        \App\Models\HeroSlide::class => 'hero_slides',
        \App\Models\DidYouKnowFact::class => 'did_you_know',
        \App\Models\TimelineEvent::class => 'timeline',
        \App\Models\Batch::class => 'batches',
        \App\Models\OrganizationStructure::class => 'organization',
        \App\Models\Activity::class => 'activities',
        \App\Models\Album::class => 'albums',
        \App\Models\Photo::class => 'albums',
    ];

    public function __construct(
        protected CacheVersionService $cacheVersionService
    ) {}

    public function created(Model $model): void
    {
        $this->invalidateCache($model);
    }

    public function updated(Model $model): void
    {
        $this->invalidateCache($model);
    }

    public function deleted(Model $model): void
    {
        $this->invalidateCache($model);
    }

    protected function invalidateCache(Model $model): void
    {
        $modelClass = get_class($model);
        $resource = self::$modelResourceMap[$modelClass] ?? null;

        if ($resource) {
            $this->cacheVersionService->incrementVersion($resource);
        }
    }
}
