<?php

namespace App\Providers;

use App\Models\Activity;
use App\Models\Album;
use App\Models\Batch;
use App\Models\DidYouKnowFact;
use App\Models\HeroSlide;
use App\Models\OrganizationStructure;
use App\Models\Photo;
use App\Models\Setting;
use App\Models\TimelineEvent;
use App\Observers\CacheInvalidationObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production (cPanel with SSL/proxy)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
            
            // Trust proxy headers for cPanel/CloudFlare
            if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
                $this->app['request']->server->set('HTTPS', 'on');
            }
        }

        // Register cache invalidation observers for all content models
        $models = [
            Setting::class,
            HeroSlide::class,
            DidYouKnowFact::class,
            TimelineEvent::class,
            Batch::class,
            OrganizationStructure::class,
            Activity::class,
            Album::class,
            Photo::class,
        ];

        foreach ($models as $model) {
            $model::observe(CacheInvalidationObserver::class);
        }
    }
}
